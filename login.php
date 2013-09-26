<?php

include 'config.php';
include 'password_hash.php';

// User is already logged in. Redirect them somewhere useful.
//if (isset($_SESSION['user_id']))
//{
//    header('Location: index.php');
//    exit();
//}

$hasher = new PasswordHash(8, FALSE);

if (!empty($_POST))
{
    $query = "SELECT id, password, UNIX_TIMESTAMP(created) AS salt
              FROM users
              WHERE username = :username";
    $stmt = $sql_conn->prepare($query);
    $stmt->execute(array(':username' => $_POST['username']));
    $user = $stmt->fetchObject();

    $remember_me = $_POST['remember_me'];

    /**
     * Check that the query returned a result (otherwise user doesn't exist)
     * and that provided password is correct.
     */
    if ($user && $user->password == $hasher->CheckPassword($_POST['password'], $user->password))
    {
        if ($remember_me == "on")
        {
            setcookie("user_id", "test", $cookie_expire);
        }
        else
        {
            session_regenerate_id();
            $_SESSION['user_id']   = $user->id;
            $_SESSION['loggedIn']  = TRUE;
            $_SESSION['signature'] = md5($user->id . $_SERVER['HTTP_USER_AGENT'] . $user->salt);
        }

        echo "Cookie:";
        print_r($_COOKIE);
        echo "<br>";
        echo "Session:";
        Print_r ($_SESSION);
    }
    else
    {
        $error = "Login failed.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>User Login</title>
</head>
<body>
<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset id="login">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" /><br />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" /><br />

        <input type="checkbox" name="remember_me" /> Remember me <br />

        <input type="submit" value="Login" />
    </fieldset>
</form>
</body>
</html>