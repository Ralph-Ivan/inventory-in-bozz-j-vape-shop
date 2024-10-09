<?php 
session_start(); 

// Set the timezone to Philippine time
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
    header("location: table.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="Inventmng/srtdash/assets/bootstrap.min.css">
    <style>
        body {
            background-color: #fff3e6; /* Soft peach background */
            color: #333; /* Dark text color */
        }

        .sidebar-menu {
            background-color: #5d8aa8; /* Teal sidebar background */
        }

        .sidebar-menu a {
            color: #ffffff; /* White text color for links */
        }

        .sidebar-menu a:hover {
            background-color: #4b7f92; /* Darker teal on hover */
        }

        .header-area {
            background-color: #ff6f61; /* Coral header background */
            color: #ffffff; /* White text color */
        }

        .footer-area {
            background-color: #3e5b78; /* Dark slate blue footer background */
            color: #ffffff; /* White text color */
        }

        .user-profile .user-name {
            color: #ffffff; /* User name color */
        }

        .button {
            background-color: #f39c12; /* Orange button background */
            color: white; /* Button text color */
            border: none; /* No border */
            padding: 10px 20px; /* Padding */
            border-radius: 5px; /* Rounded corners */
        }

        .button:hover {
            background-color: #e67e22; /* Darker orange on hover */
        }

        h2 {
            text-align: center;
            color: #5d8aa8; /* Teal heading color */
        }

        .date-time {
            float: right;
            margin-right: 20px;
            font-size: 16px;
            color:black;
        }
    </style>
</head>
<body>

<div class="content">
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success">
            <h3>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>
</div>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="page-container">
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index.php"><img src="3r.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="index.php" aria-expanded="true"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                            <li>
                                <a href="table.php" aria-expanded="true"><i class="fa fa-table"></i>
                                    <span>Item Records</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area pull-right">
                        </ul>
                    </div>
                    <div class="col-md-6 clearfix">
                        <div class="date-time">
                            <?php echo date('l, F j, Y - h:i A'); // Displaying the date and time ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index.php">Home</a></li>
                                <li><span>Dashboard</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['username']?> <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="confirmLogout()">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <?php if (isset($_SESSION['first_name'])) : ?>
                <h2>Welcome To Bozz J Vape Shop <strong><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></strong></h2>
                <div class="cover">
                    <a href="index.php"><img src="bozzz.jpg" alt="cover"></a>
                <?php endif ?>
                </div>
            </div>

            <footer>
                <div class="footer-area">
                    <p>All rights reserved. Bozz J Concepcion Branch.</p>
                </div>
            </footer>
        </div>

        <div class="offset-area">
            <div class="offset-close"><i class="ti-close"></i></div>
            <ul class="nav offset-menu-tab">
                <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
                <li><a data-toggle="tab" href="#settings">Settings</a></li>
            </ul>
        </div>

        <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/owl.carousel.min.js"></script>
        <script src="assets/js/metisMenu.min.js"></script>
        <script src="assets/js/jquery.slimscroll.min.js"></script>
        <script src="assets/js/jquery.slicknav.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
        <script>
        zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
        </script>

        <script src="assets/js/line-chart.js"></script>
        <script src="assets/js/pie-chart.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="assets/js/scripts.js"></script>

        <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "index.php?logout='1'";
            }
        }
        </script>
    </body>
</html>
