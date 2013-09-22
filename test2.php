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
    runMainDemo($analytics);
}

function runMainDemo(&$analytics)
{
    try {
        $profileId = getFirstProfileId($analytics);

        if (isset($profileId)) {
            $results = getResults($analytics, $profileId);
            printResults($results);
        }

    } catch (apiServiceException $e) {
// Error from the API.
        print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

    } catch (Exception $e) {
        print 'There was a general error : ' . $e->getMessage();
    }
}

function getFirstprofileId(&$analytics)
{
    $accounts = $analytics->management_accounts->listManagementAccounts();

    if (count($accounts->getItems()) > 0) {
        $items = $accounts->getItems();
        $firstAccountId = $items[0]->getId();
        $firstAccountName = $items[0]->getName();

        $webproperties = $analytics->management_webproperties
            ->listManagementWebproperties($firstAccountId);

        print "<p>firstAccountName: $firstAccountName</p>";

        if (count($webproperties->getItems()) > 0) {
            $items = $webproperties->getItems();
            $firstWebpropertyId = $items[43]->getId();
            $firstWebpropertyName = $items[43]->getName();

            $profiles = $analytics->management_profiles
                ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

            print "<p>firstWebpropertyName: $firstWebpropertyName</p>";

            if (count($profiles->getItems()) > 0) {
                $items = $profiles->getItems();
                return $items[0]->getId();

            } else {
                throw new Exception('No views (profiles) found for this user.');
            }
        } else {
            throw new Exception('No webproperties found for this user.');
        }
    } else {
        throw new Exception('No accounts found for this user.');
    }
}

function getResults(&$analytics, $profileId)
{
    return $analytics->data_ga->get(
        'ga:' . $profileId,
        '2013-08-21',
        '2013-09-21',
        'ga:visits');
}

function printResults(&$results)
{
    if (count($results->getRows()) > 0) {
        $profileName = $results->getProfileInfo()->getProfileName();
        $rows = $results->getRows();
        $visits = $rows[0][0];

        print "<p>First view (profile) found: $profileName</p>";
        print "<p>Total visits: $visits</p>";

    } else {
        print '<p>No results found.</p>';
    }
}