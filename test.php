<?php
session_start();
require_once dirname(__FILE__). '/google-api-php-client/src/Google_Client.php';
require_once dirname(__FILE__). '/google-api-php-client/src/contrib/Google_AnalyticsService.php';

$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];

$client = new Google_Client();
$client->setAccessType('online'); // default: offline
$client->setApplicationName('My Application name');
$client->setClientId('925815342836.apps.googleusercontent.com');
$client->setClientSecret('7KqGnzQxESIQb8mB03Ksz2Ef');
$client->setRedirectUri($scriptUri);
$client->setDeveloperKey('AIzaSyDt1kBvVay70jHmV_agl7Gt28Be1WaNLoc'); // API key

// $service implements the client interface, has to be set before auth call
$service = new Google_AnalyticsService($client);

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
    die('Logged out.');
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $client->setAccessToken($token);
}

if (!$client->getAccessToken()) { // auth call to google
    $authUrl = $client->createAuthUrl();
    header("Location: ".$authUrl);
    die;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    <title>Business Dashboard App</title>
</head>
<body>

<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Brand</a>
</div>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Business Dashboard App</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<br>
<br>

    <?php
    try {
        $props = $service->management_webproperties->listManagementWebproperties("~all");
        echo '<h1>Available Google Analytics projects</h1><ul>'."\n";
        foreach($props['items'] as $item) printf($item['name'].' --- '.$item['id'].' --- '.$item['internalWebPropertyId'].'<br>');
        echo '</ul>';
    } catch (Exception $e) {
        die('An error occured: ' . $e->getMessage()."\n");
    }
    ?>

    <?php
    $projectId = '56472596';

    // metrics
    $_params[] = 'date';
    $_params[] = 'date_year';
    $_params[] = 'date_month';
    $_params[] = 'date_day';
    // dimensions
    $_params[] = 'visits';
    $_params[] = 'pageviews';
    $_params[] = 'bounces';
    $_params[] = 'entrance_bounce_rate';
    $_params[] = 'visit_bounce_rate';
    $_params[] = 'avg_time_on_site';

    $from = date('Y-m-d', time()-2*24*60*60); // 2 days
    $to = date('Y-m-d'); // today

    $metrics = 'ga:visits,ga:pageviews,ga:bounces,ga:entranceBounceRate,ga:visitBounceRate,ga:avgTimeOnSite';
    $dimensions = 'ga:date,ga:year,ga:month,ga:day';
    $data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions));

    foreach($data['rows'] as $row) {
    $dataRow = array();
    foreach($_params as $colNr => $column) echo $column . ': '.$row[$colNr].', ';
    }
    ?>

</div><!-- /.container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>