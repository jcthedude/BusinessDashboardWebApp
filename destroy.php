<?php

include 'modules/config.php';

unset($_SESSION["username"]);
unset($_SESSION["loggedIn"]);
unset($_SESSION["signature"]);
session_destroy();
setcookie("signature", "", time()-3600);

echo "Cookie:";
print_r($_COOKIE);
echo "<br>";
echo "Session:";
Print_r ($_SESSION);

phpinfo();

?>
