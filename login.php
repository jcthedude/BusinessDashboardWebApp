<?php

include_once("modules/config.php");
include_once("modules/func.user.php");
include_once("modules/class.user.php");

if(loggedIn()):
    header('Location: members.php');
    exit();
endif;

$hasher = new PasswordHash(8, FALSE);

if(isset($_POST["login"])):
    $query = $coll->findOne(array('username' => $_POST['username']));

    if (isset($query['password']) && $query['password'] == $hasher->CheckPassword($_POST['password'], $query['password'])):
        cleanMemberSession($_POST["username"], $_POST["remember_me"]);
        echo'<script> window.location="members.php"; </script> ';
    else:
        $error = "Incorrect login/password, try again";
    endif;
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
                            Login to Littix
                        </div>
                        <p>
                            <a class="btn btn-facebook"><span>Login via Facebook</span></a>
                        </p>
                        <p>
                            <a class="btn btn-twitter"><span>Login via Twitter</span></a>
                        </p>

                        <div class="text-with-hr">
                            <span>or use your username</span>
                        </div>

                        <form class="form-horizontal login" action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="text" class="form-control" name="username" placeholder="Username"  maxlength="30"/>
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="password" class="form-control" name="password" placeholder="Password" maxlength="30"/>
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($error)): ?>
                                    <div class="row">
                                        <a class="pull-left"><?php echo $error; ?></a>
                                    </div>
                                <?php endif; ?>

                                <div class="confirm">
                                    <input type="checkbox" name="remember_me"/>
                                    <label for="remember_me">Remember me</label>
                                </div>

                                <div class="row">
                                    <button type="submit" name="login" class="btn btn-lg btn-primary col-xs-12">Login</button>
                                </div>

                            </fieldset>

                        </form>

                        <a class="pull-left" href="password-reset.php">Forgot Password?</a>
                        <a class="pull-right" href="register.php">Sign Up!</a>

                        <div class="clearfix"></div>

                    </div><!--/login-box-->
                </div><!--/row-->
            </div><!--/content-->
        </div><!--/row-->
    </div><!--/container-->
    <?php include_once 'footer.php'; ?>
</body>
</html>