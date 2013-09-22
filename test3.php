<?php
require_once dirname(__FILE__) . '/google-api-php-client/src/Google_Client.php';
require_once dirname(__FILE__) . '/google-api-php-client/src/contrib/Google_AnalyticsService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName('Hello Analytics API Sample');

$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];

// Visit https://cloud.google.com/console to generate your
// client id, client secret, and to register your redirect uri.
$client->setAccessType('online'); // default: offline
$client->setApplicationName('My Application name');
$client->setClientId('925815342836.apps.googleusercontent.com');
$client->setClientSecret('7KqGnzQxESIQb8mB03Ksz2Ef');
$client->setRedirectUri($scriptUri);
$client->setDeveloperKey('AIzaSyDt1kBvVay70jHmV_agl7Gt28Be1WaNLoc'); // API key
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

// Magic. Returns objects from the Analytics Service instead of associative arrays.
$client->setUseObjects(true);

if (isset($_GET['code'])) {
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    $redirect = $scriptUri;
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()) {
    $authUrl = $client->createAuthUrl();
    print "<a class='login' href='$authUrl'>Connect Me!</a>";

} else {
    $analytics = new Google_AnalyticsService($client);
    getProfileId($analytics);
}

function getProfileId(&$analytics)
{
    $profiles = $analytics->management_profiles->listManagementProfiles('~all', '~all');

    $items = $profiles->getItems();
    foreach($items as $item) {
        $ProfileId = $item->getId();
        $ProfileName = $item->getName();
        echo $ProfileName.' --- '.$ProfileId.'<br>';
    }
}