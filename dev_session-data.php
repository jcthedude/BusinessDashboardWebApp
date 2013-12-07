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
include_once("modules/func.yelp.views.php");
include_once("modules/func.places.views.php");
include_once("modules/func.citysearch.views.php");

$result_citysearch = getCitysearchReviewScore();

echo 'Details:';
echo '<br>';
echo '<pre>';
print_r($result_citysearch);
echo '</pre>';
echo '<br><br>';

?>