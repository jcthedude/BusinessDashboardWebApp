<?php

include_once("modules/config.php");
include_once("modules/class.user.php");

if(loggedIn()):
    header('Location: members.php');
    exit();
endif;

if(isset($_POST["submit"])):
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

if (isset($_POST["submit"]) && empty($errors)):
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

<html>
<head>
    <title>Simple Register with MongoDB</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Login:
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
                Email Address:
            </td>
            <td>
                <input type="email" name="email" value="<?php print isset($_POST["email"]) ? $_POST["email"] : "" ; ?>">
                    <span class="error">
                        <?php echo isset($errors['email']) ? $errors['email'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Password:
            </td>
            <td>
                <input type="password" name="password" value="" maxlength="30">
                    <span class="error">
                        <?php echo isset($errors['password']) ? $errors['password'] : ''; ?>
                    </span><br />
            </td>
        </tr>
        <tr>
            <td>
                Confirm password:
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