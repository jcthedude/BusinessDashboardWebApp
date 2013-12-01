<?php

include_once("modules/config.php");
include_once("modules/func.user.php");
include_once("modules/class.user.php");

if(isset($_POST["reset"])):
    // First check that required fields have been filled in.
    if (empty($_POST['username'])):
        $errors['username'] = "Username cannot be empty.";
    endif;
    if (empty($_POST['email'])):
        $errors['email'] = "Email address cannot be empty.";
    endif;
endif;

if (isset($_POST["reset"]) && empty($errors)):
    $user_password = mt_rand();
    $hasher = new PasswordHash(8, FALSE);
    $password = $hasher->HashPassword($user_password);
    $token = md5(uniqid(mt_rand(), true));

    $query = $coll->findOne(array('username' => $_POST['username'], 'email' => $_POST['email']));

    if ($query):
        passwordChange($query["username"], $password, $token);
        flushMemberSession();
    else:
        $errors['general'] = "Incorrect username/email, try again";
    endif;
endif;

if(isset($_POST["login"])):
    echo'<script> window.location="login.php"; </script> ';
endif;

?>

<?php include_once 'header.php'; ?>
</head>
<body>
<div class="container">
    <div class="row">
        <div id="content" class="col-sm-12 full">
            <div class="row">
                <div class="login-box">

                    <div class="header">
                        Reset Your Littix Password
                    </div>

                    <form class="form-horizontal login" action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
                        <fieldset class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group col-sm-12">
                                    <input type="text" class="form-control" name="username" placeholder="Username"  maxlength="30"/>
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                            </div>

                            <div class="row">
                                <span class="label label-important"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
                            </div>

                            <div class="form-group">
                                <div class="input-group col-sm-12">
                                    <input type="text" class="form-control" name="email" placeholder="Email"/>
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                </div>
                            </div>

                            <div class="row">
                                <span class="label label-important"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                            </div>

                            <div class="row">
                                <button type="submit" name="reset" class="btn btn-lg btn-primary col-xs-12">Reset Password</button>
                            </div>
                        </fieldset>
                    </form>

                    <?php if (isset($user_password)): ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Password Changed! </strong> Your new password is: <?php echo $user_password; ?>
                        </div>

                        <form class="form-horizontal login" action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
                            <fieldset class="col-sm-12">
                                <div class="row">
                                    <button type="submit" name="login" class="btn btn-lg btn-success col-xs-12">Login</button>
                                </div>
                            </fieldset>
                        </form>
                    <?php endif; ?>

                    <div class="clearfix"></div>

                </div><!--/login-box-->
            </div><!--/row-->
        </div><!--/content-->
    </div><!--/row-->
</div><!--/container-->
<?php include_once 'footer.php'; ?>
</body>
</html>