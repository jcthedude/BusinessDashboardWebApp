<?php

include 'modules/config.php';

flushMemberSession();
setcookie("user", "", time()-3600);
setcookie("token", "", time()-3600);

echo "Cookie:";
print_r($_COOKIE);
echo "<br>";
echo "Session:";
Print_r ($_SESSION);

phpinfo();

?>
