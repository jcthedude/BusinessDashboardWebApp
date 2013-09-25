<?php

session_start();

include_once 'config.php';

// User is already logged in. Redirect them somewhere useful.
if (isset($_SESSION['user_id']))
{
    header('Location: index.php');
    exit();
}

include 'password_hash.php';

$sql = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

$hasher = new PasswordHash(8, FALSE);

if (!empty($_POST))
{
    $query = "SELECT id, password, UNIX_TIMESTAMP(created) AS salt
              FROM users
              WHERE username = :username";
    $stmt = $sql->prepare($query);
    $stmt->execute(array(':username' => $_POST['username']));
    $user = $stmt->fetchObject();

    /**
     * Check that the query returned a result (otherwise user doesn't exist)
     * and that provided password is correct.
     */
    if ($user && $user->password == $hasher->CheckPassword($_POST['password'], $user->password))
    {
        /**
         * Set cookies here if/as needed.
         * Set session data as needed. DO NOT store user's password in
         * cookies or sessions!
         * Redirect the user if/as required.
         */
        session_regenerate_id();
        $_SESSION['user_id']   = $user->id;
        $_SESSION['loggedIn']  = TRUE;
        $_SESSION['signature'] = md5($user->id . $_SERVER['HTTP_USER_AGENT'] . $user->salt);

        Print_r ($_SESSION);
    }
    /**
     * Don't provide specific details as to whether username or password was
     * incorrect. If an attacker knows they've found a valid username, you've
     * just made their life easier.
     */
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

        <input type="submit" value="Login" />
    </fieldset>
</form>
</body>
</html>