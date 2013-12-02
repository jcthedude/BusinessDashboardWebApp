<?php

include_once("modules/config.php");
include_once("modules/func.user.php");
include_once("modules/class.user.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
   $username = $_SESSION["username"];
endif;

?>

<!-- start: Header -->
<header class="navbar">
    <div class="container">
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".sidebar-nav.nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a id="main-menu-toggle" class="hidden-xs open"><i class="fa fa-bars"></i></a>
    <a class="navbar-brand col-md-2 col-sm-1 col-xs-2" href="dashboard.php"><span>Littix</span></a>

    <!-- start: Header Menu -->
    <div class="nav-no-collapse header-nav">
    <ul class="nav navbar-nav pull-right">

    <!-- start: User Dropdown -->
    <li class="dropdown">
        <a class="btn account dropdown-toggle" data-toggle="dropdown" href="dashboard.php">
            <div class="avatar"></div>
            <div class="user">
                <span class="hello">Welcome, </span>
                <span class="name"><?php echo isset($username) ? $username : 'No User Logged In'; ?></span>
            </div>
        </a>
        <ul class="dropdown-menu">
            <li><a href="email-change.php"><i class="fa fa-envelope"></i> Change Email</a></li>
            <li><a href="password-change.php"><i class="fa fa-key"></i> Change Password</a></li>
            <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
        </ul>
    </li>
    <!-- end: User Dropdown -->

    </ul>
    </div>

    <!-- end: Header Menu -->

    </div>
</header>
<!-- end: Header -->