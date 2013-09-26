<?php

session_start();
session_destroy();
setcookie("user_id", "", $cookie_expire);

echo "Cookie:";
print_r($_COOKIE);
echo "<br>";
echo "Session:";
Print_r ($_SESSION);