<?php

include_once("modules/config.php");
include_once("modules/class.user.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
endif;

$query = $coll->findOne(array('username' => $_SESSION["username"]));

if(isset($_POST["submit"])):
    // First check that required fields have been filled in.
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (empty($_POST['email'])):
        $errors['email'] = "Email address cannot be empty.";
    endif;
    if(!$email):
        $errors['email'] = "Not a valid email address.";
    endif;
endif;

if (isset($_POST["submit"]) && empty($errors)):
    $token = md5(uniqid(mt_rand(), true));

    if ($query):
        $old_email = $query["email"];

        emailChange($query["username"], $_POST['email'], $token);
        cleanMemberSession($query["username"], "on");
        sendMail($_POST['email'], $old_email, "", "email-change");

        echo "Your email address has been changed to: " . $_POST['email'];
        print("</br><a href=\"dashboard.php"."\">Members Area</a>");
        exit();
    else:
        $errors['general'] = "There was an error, try again";
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
                <?php if (isset($query["username"])): ?>
                    <p><?php echo $query["username"]; ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                Current Email Address:
            </td>
            <td>
                <?php if (isset($query["email"])): ?>
                    <p><?php echo $query["email"]; ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                New Email Address:
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
                <input name="submit" type="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
</body>
</html>