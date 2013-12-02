<?php

include_once("modules/config.php");
include_once("modules/func.user.php");
include_once("modules/class.user.php");

flushMemberSession();
echo '<script> window.location="dashboard.php"; </script> ';

?>