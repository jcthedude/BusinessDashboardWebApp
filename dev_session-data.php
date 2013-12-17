<?php

include_once("modules/config.php");

echo "Session";
echo "<br>";
print_r($_SESSION);

echo "<br><br>";

echo "Cookies";
echo "<br>";
print_r($_COOKIE);

include_once("modules/func.yelp.php");

$location_yelp = "everett,+wa";
$business_yelp = "olympic+view+chiropractic";
$result_yelp = makeYelpAPIRequestSearch($location_yelp, $business_yelp);

echo 'Details:';
echo '<br>';
echo '<pre>';
print_r($result_yelp);
echo '</pre>';
echo '<br><br>';

?>