<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "inventorymanagement");

// Check for a database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding products
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_name'])) {
    // Retrieve form data
    $productName = $conn->real_escape_string($_POST['product_name']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quant']);

    // Insert data into the database
    $sql = "INSERT INTO product (product_name, price, quantity) VALUES ('$productName', '$price', '$quantity')";

    // Check if the query was successful
    if ($conn->query($sql) === TRUE) {
        $_SESSION['msg'] = "New product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle search functionality
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
}

// Fetch product data to display in the table with optional search filter
$sql = "SELECT * FROM product WHERE product_name LIKE '%$searchQuery%'";
$result = $conn->query($sql);
$totalProducts = $result->num_rows; // Total count of products

$conn->close();

// Export functionality
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="products.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add column headers
    fputcsv($output, ['ID', 'Name', 'Price', 'Quantity', 'Status']);

    // Reconnect to the database to fetch data
    $conn = new mysqli("localhost", "root", "", "inventorymanagement");
    $result = $conn->query("SELECT * FROM product");
    while ($row = $result->fetch_assoc()) {
        // Determine stock status
        if ($row['quantity'] < 10) {
            $status = 'Low Stock';
        } elseif ($row['quantity'] >= 10 && $row['quantity'] <= 100) {
            $status = 'In Stock';
        } else {
            $status = 'Overstock';
        }

        fputcsv($output, [$row['product_id'], $row['product_name'], $row['price'], $row['quantity'], $status]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Records</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f0f0f0; }
        header { background-color: #333; color: #fff; padding: 20px; text-align: center; position: relative; }
        header h1 { margin: 0; }
        header input[type="search"] { width: 50%; height: 30px; padding: 10px; font-size: 16px; border: none; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        #add-item { background-color: #fff; padding: 10px; margin: 20px auto; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 400px; }
        #add-item h2 { margin-top: 0; }
        #add-item label { display: block; margin-bottom: 5px; }
        #add-item input[type="text"], #add-item input[type="number"] { width: calc(100% - 20px); height: 28px; padding: 5px; font-size: 14px; margin-bottom: 10px; }
        #add-item input[type="submit"] { width: 100%; height: 36px; padding: 10px; font-size: 16px; background-color: #333; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        #add-item input[type="submit"]:hover { background-color: #444; }
        #products { background-color: #fff; padding: 20px; margin: 20px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        td { background-color: #f2f2f2; }
        .low-stock { background-color: #ffcccb; }
        .in-stock { background-color: #d4edda; }
        .overstock { background-color: #ffeeba; }
        .total-products { margin: 20px; font-size: 20px; }
        #color-change {
            position: absolute;
            top: 5px;
            right: 5px;
        }
        #change-color {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            background-color: #2196F3;
            color: white;
            font-size: 18px;
            box-shadow: 10 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }
        #change-color:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            transform: translateY(-2px);
        }
        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php" style="text-decoration: none;">
        <button style="background-color: #4CAF50; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; position: absolute; left: 20px; font-size: 24px;">&#8592;</button>
    </a>
    <h1>Item Records</h1>
    <form method="GET" action="" style="display: inline;">
        <input type="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <input type="submit" value="Search" style="display: none;">
    </form>
</header>

<!-- Color Change Button -->
<section id="color-change">
    <button id="change-color" title="Change Colors"></button>
</section>

<!-- Total Count of Products -->
<div class="total-products">
    Total Products: <strong><?php echo $totalProducts; ?></strong>
</div>

<!-- Form to add items -->
<section id="add-item">
    <h2>Add Item Here</h2>
    <form method="POST" action="">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="product_name" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quant" min="1" required>

        <input type="submit" value="Add Item">
    </form>
</section>

<!-- Products Table Section -->
<section id="products">
    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>PRICE</th>
                <th>QUANTITY</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetching and displaying products
            if ($result->num_rows > 0) {
                $count = 0;
                while ($row = $result->fetch_assoc()) {
                    $count++;
                    // Determine stock status
                    $statusClass = '';
                    $indicator = '';
                    if ($row['quantity'] < 10) {
                        $statusClass = 'low-stock';
                        $indicator = '🔴 Low Stock';
                        $quantityDisplay = "{$row['quantity']} ⚠️"; 
                    } elseif ($row['quantity'] >= 10 && $row['quantity'] <= 100) {
                        $statusClass = 'in-stock';
                        $indicator = '🟢 In Stock';
                        $quantityDisplay = $row['quantity'];
                    } else {
                        $statusClass = 'overstock';
                        $indicator = '🟡 Overstock';
                        $quantityDisplay = $row['quantity'];
                    }

                    echo "<tr class='{$statusClass}'>
                            <td>{$count}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['price']}</td>
                            <td>{$quantityDisplay}</td>
                            <td>{$indicator}</td>
                            <td>
                                <div class='dropdown'>
                                    <button class='dropbtn'>...</button>
                                    <div class='dropdown-content'>
                                        <a href='edit.php?id={$row['product_id']}'>Edit</a>
                                        <a href='delete.php?id={$row['product_id']}'>Delete</a>
                                    </div>
                                </div>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<!-- Export Button -->
<section id="export">
    <a href="?export=true">
        <button style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 20px;">Export to CSV</button>
    </a>
</section>

<header>
    <a href="index.php">
        <button style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">Go to Dashboard</button>
    </a>
</header>

<script>
    // JavaScript for changing colors
    const colors = [
        { background: '#f0f0f0', text: '#000' },
        { background: '#ffcccb', text: '#000' },
        { background: '#d4edda', text: '#000' },
        { background: '#ffeeba', text: '#000' },
        { background: '#e7f3fe', text: '#000' },
        { background: '#fff3cd', text: '#000' }
    ];

    document.getElementById('change-color').onclick = function() {
        const randomColor = colors[Math.floor(Math.random() * colors.length)];
        document.body.style.backgroundColor = randomColor.background;
        document.body.style.color = randomColor.text;
    };
</script>

</body>
</html>
