<?php

//Google Analytics API variables
$client_id                  = '925815342836.apps.googleusercontent.com';
$client_secret              = '7KqGnzQxESIQb8mB03Ksz2Ef';
$redirect_uri               = 'http://localhost:8888/business_dashboard_app/test4.php';
$api_key                    = 'AIzaSyCKp12gtsmUTAGPIQL0um_zlLgT03CJHg4';
$selected_profile           = '69242945';
$max_results                = '100';


//Get refresh token
if(isset($_GET['code'])) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'code='.$_GET['code'].'&client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri='.$redirect_uri.'&grant_type=authorization_code');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $result_token = json_decode($data, true);
    $access_token = $result_token['access_token'];

    var_dump($result_token);
    echo "<br>";
    var_dump($access_token);
    echo "<br>";
}
else {
    var_dump($_REQUEST, true);
}


//Get access token using refresh token
//$ch = curl_init();
//$timeout = 5;
//curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, 'refresh_token='.$refresh_token.'&client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//$data = curl_exec($ch);
//curl_close($ch);
//$result = json_decode($data, true);
//
//var_dump($result);


//Get account info
if(isset($result_token['access_token'])) {
    $url = 'https://www.googleapis.com/analytics/v3/management/accounts?key='.$api_key;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $result_token['access_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $result_account = json_decode($data, true);

    foreach ($result_account['items'] as $item) {
        echo $item['id']."<br>";
    }
}


//Get profiles for account
if(isset($result_token['access_token'])) {
    $url = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles?key='.$api_key;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $result_token['access_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $result_profile = json_decode($data, true);

    foreach ($result_profile['items'] as $item) {
        echo $item['name'].'---'.$item['id']."<br>";
    }
}


//Get visitors for last 30 days
if(isset($result_token['access_token'])) {
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 day'));
    $total_visitors = 0;

    $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors&dimensions=ga:date&max-results='.$max_results;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $result_token['access_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $result_visitors = json_decode($data, true);

    foreach ($result_visitors['rows'] as $item) {
        $item['date'] = $item[0];
        unset($item[0]);

        $item['visitors'] = $item[1];
        unset($item[1]);

        $total_visitors += $item['visitors'];

        echo $item['date'].'---'.$item['visitors']."<br>";
    }

    echo "Total---".$total_visitors."<br>";
}

?>