<?php

include_once("modules/config.php");

flushMemberSession();
setcookie("user", "", time()-3600);
setcookie("token", "", time()-3600);

header('Location: index.php');

?>