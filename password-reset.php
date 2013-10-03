<?php

include_once("modules/config.php");
include_once("modules/class.user.php");

if(isset($_POST["submit"])):
    // First check that required fields have been filled in.
    if (empty($_POST['username'])):
        $errors['username'] = "Username cannot be empty.";
    endif;
    if (empty($_POST['email'])):
        $errors['email'] = "Email address cannot be empty.";
    endif;
endif;

if (isset($_POST["submit"]) && empty($errors)):
    $user_password = mt_rand();
    $hasher = new PasswordHash(8, FALSE);
    $password = $hasher->HashPassword($user_password);
    $token = md5(uniqid(mt_rand(), true));

    $query = $coll->findOne(array('username' => $_POST['username'], 'email' => $_POST['email']));

    if ($query):
        passwordChange($query["username"], $password, $token);
        flushMemberSession();
        echo "Your new password is: " . $user_password;
        print("</br><a href=\"login.php"."\">Login</a>");
        exit();
    else:
        $errors['general'] = "Incorrect username/email, try again";
    endif;
endif;

?>

<html>
<head>
    <title>Simple Password Reset with MongoDB</title>
</head>
<?php if (isset($errors['general'])): ?>
    <p class="error"><?php echo $errors['general']; ?></p>
<?php endif; ?>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Username:
            </td>
            <td>
                <input type="text" name="username" value="<?php print isset($_POST["username"]) ? $_POST["username"] : "" ; ?>" maxlength="30">
                    <span class="error">
                        <?php echo isset($errors['username']) ? $errors['username'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Email:
            </td>
            <td>
                <input type="text" name="email" value="<?php print isset($_POST["email"]) ? $_POST["email"] : "" ; ?>">
                    <span class="error">
                        <?php echo isset($errors['email']) ? $errors['email'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="submit" type="submit" value="Reset Password">
            </td>
        </tr>
    </table>
</form>
</body>
</html>