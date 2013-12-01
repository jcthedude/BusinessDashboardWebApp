<?php

include_once("modules/config.php");
include_once("modules/func.user.php");
include_once("modules/class.user.php");

if(loggedIn()):
    header('Location: members.php');
    exit();
endif;

if(isset($_POST["create"])):
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // First check that required fields have been filled in.
    if (empty($_POST['username'])):
        $errors['username'] = "Username cannot be empty.";
    endif;
    if (empty($_POST['password'])):
        $errors['password'] = "Password cannot be empty.";
    endif;
    if (strlen($_POST['password']) < 5):
        $errors['password'] = "Password must be at least 5 characters.";
    endif;
    if (empty($_POST['password_confirm'])):
        $errors['password_confirm'] = "Please confirm password.";
    endif;
    if ($_POST['password'] != $_POST['password_confirm']):
        $errors['password'] = "Passwords do not match.";
    endif;
    if(!$email):
        $errors['email'] = "Not a valid email address.";
    endif;
endif;

if (isset($_POST["create"]) && empty($errors)):
    $hasher = new PasswordHash(8, FALSE);
    $password = $hasher->HashPassword($_POST['password']);
    $token = md5(uniqid(mt_rand(), true));

    $query = $coll->findOne(array('$or' => array(
        array('username' => $_POST['username']),
        array('email' => $_POST['email']),
    )));

    if (empty($query)):
        newUser($_POST["username"], $password, $_POST["email"], $token);
        cleanMemberSession($_POST["username"], $_POST["remember_me"]);
        sendMail($_POST["email"], "", "register");
        header("Location: members.php");
    elseif ($query['username'] == $_POST['username']):
        $errors['username'] = "That username is already in use.";
    elseif ($query['email']  == $_POST['email']):
        $errors['email'] = "That email address is already in use.";
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
                            Create your account
                        </div>

                        <form class="form-horizontal login" action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
                            <fieldset class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="username">Username</label>
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="text" class="form-control" name="username" value="<?php print isset($_POST["username"]) ? $_POST["username"] : "" ; ?>" maxlength="30"/>
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <span class="label label-important"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="username">Email</label>
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="text" class="form-control" name="email" value="<?php print isset($_POST["email"]) ? $_POST["email"] : "" ; ?>"/>
                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <span class="label label-important"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="password">Password</label>
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="password" class="form-control" name="password" maxlength="30"/>
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <span class="label label-important"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="password">Confirm Password</label>
                                    <div class="controls row">
                                        <div class="input-group col-sm-12">
                                            <input type="password" class="form-control" name="password_confirm" maxlength="30"/>
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <span class="label label-important"><?php echo isset($errors['password_confirm']) ? $errors['password_confirm'] : ''; ?></span>
                                </div>

                                <div class="confirm">
                                    <input type="checkbox" name="remember_me"/>
                                    <label for="remember_me">Remember Me</label>
                                </div>


                                <div class="row">
                                    <button type="submit" name="create" class="btn btn-primary col-xs-12">Create Account!</button>
                                </div>
                            </fieldset>

                        </form>

                        <div class="text-with-hr">
                            <span>or use your facebook account</span>
                        </div>

                        <p>
                            <a class="btn btn-facebook"><span>Register via Facebook</span></a>
                        </p>

                    </div><!--/login-box-->
                </div><!--/row-->
            </div><!--/content-->
        </div><!--/row-->
    </div><!--/container-->
<?php include_once 'footer.php'; ?>
</body>
</html>