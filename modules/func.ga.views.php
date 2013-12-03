<?php

include_once("config.php");
include_once("func.ga.php");

function getPageViews()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    $refresh_token = $query['ga_refresh_token'];
    $property = $query['ga_web_property'][0]['ga_property_id'];
    $access_token = getAccessToken($refresh_token);

    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 day'));

    if(isset($property)):
        //Get visits for the last month
        $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$property.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:pageviews';
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);

        return $result['totalsForAllResults']['ga:pageviews'];
    endif;
}

function getUniqueVisitors()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    $refresh_token = $query['ga_refresh_token'];
    $property = $query['ga_web_property'][0]['ga_property_id'];
    $access_token = getAccessToken($refresh_token);

    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 day'));

    if(isset($property)):
        //Get visits for the last month
        $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$property.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors';
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);

        return $result['totalsForAllResults']['ga:visitors'];
    endif;
}

?>