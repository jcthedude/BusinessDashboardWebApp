<?php

include_once("modules/config.php");
include_once("modules/class.user.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
endif;

if(isset($_POST["submit"])):
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // First check that required fields have been filled in.
    if (empty($_POST['password_old'])):
        $errors['password_old'] = "Old password cannot be empty.";
    endif;
    if (empty($_POST['password_new'])):
        $errors['password_new'] = "New password cannot be empty.";
    endif;
    if (strlen($_POST['password_new']) < 5):
        $errors['password_new'] = "Password must be at least 5 characters.";
    endif;
    if (empty($_POST['password_confirm'])):
        $errors['password_confirm'] = "Please confirm password.";
    endif;
    if ($_POST['password_new'] != $_POST['password_confirm']):
        $errors['password_new'] = "Passwords do not match.";
    endif;
endif;

if (isset($_POST["submit"]) && empty($errors)):
    $hasher = new PasswordHash(8, FALSE);
    $password = $hasher->HashPassword($_POST['password_new']);
    $token = md5(uniqid(mt_rand(), true));

    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if (isset($query['password']) && $query['password'] == $hasher->CheckPassword($_POST['password_old'], $query['password'])):
        passwordChange($query["username"], $password, $token);
        cleanMemberSession($query["username"], $_POST["remember_me"]);
        sendMail($query["email"], "", "", "password-change");
        header("Location: members.php");
    else:
        $errors['password_old'] = "Old password is incorrect.";
    endif;
endif;

?>

<html>
<head>
    <title>Simple Password Change with MongoDB</title>
</head>
<body>
<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Old Password:
            </td>
            <td>
                <input type="password" name="password_old" value="" maxlength="30">
                    <span class="error">
                        <?php echo isset($errors['password_old']) ? $errors['password_old'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                New Password:
            </td>
            <td>
                <input type="password" name="password_new" value="" maxlength="30">
                    <span class="error">
                        <?php echo isset($errors['password_new']) ? $errors['password_new'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Confirm Password:
            </td>
            <td>
                <input type="password" name="password_confirm" value="" maxlength="30">
                    <span class="error">
                        <?php echo isset($errors['password_confirm']) ? $errors['password_confirm'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Remember Me:
            </td>
            <td>
                <input type="checkbox" name="remember_me">
            </td>
        </tr>
        <tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="submit" type="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
</body>
</html>