<?php

include_once("modules/config.php");

echo "Session";
echo "<br>";
print_r($_SESSION);

echo "<br><br>";

echo "Cookies";
echo "<br>";
print_r($_COOKIE);

include_once("modules/func.ga.views.php");
include_once("modules/func.facebook.views.php");
include_once("modules/func.twitter.views.php");

$page_views = getPageViewsVisitors();

echo 'Details:';
echo '<br>';
echo '<pre>';
print_r($page_views);
echo '</pre>';
echo '<br><br>';

?>