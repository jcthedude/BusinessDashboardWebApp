<?php

include_once('modules/config.php');
include_once("modules/func.user.php");

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
endif;

?>

<?php include_once 'header.php'; ?>
</head>
<body>
<?php include_once 'navbar.php'; ?>
<?php include_once 'menubar.php'; ?>

<!-- start: Content -->
<div id="content" class="col-lg-10 col-sm-11 ">
    <div class="row">
        <div class="col-xs-12">

            <?php include_once 'setup_yelp.php'; ?>

        </div><!--/col-->
    </div><!--/row-->

    <br><br>

</div> <!-- end: Content -->
<?php include_once 'footer.php'; ?>

<!-- inline scripts related to this page -->

</body>
</html>