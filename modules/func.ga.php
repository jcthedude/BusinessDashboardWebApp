<?php

function getVisitsYear($access_token, $selected_profile)
{
    global $max_results;
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-365 day'));

    //Get visits for the last year
    $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors,ga:newVisits&dimensions=ga:year,ga:month&max-results='.$max_results;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getVisitsMonth($access_token, $selected_profile)
{
    global $max_results;
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 day'));

    //Get visits for the last month
    $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors,ga:newVisits&dimensions=ga:date&max-results='.$max_results;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getSourcesYear($access_token, $selected_profile)
{
    global $max_results;
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-365 day'));

    //Get sources for the last year
    $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors&dimensions=ga:source&sort=-ga:visitors&max-results='.$max_results;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getSourcesMonth($access_token, $selected_profile)
{
    global $max_results;
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 day'));

    //Get sources for the last month
    $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors&dimensions=ga:source&sort=-ga:visitors&max-results='.$max_results;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getWebProperties($access_token)
{
    if (empty($access_token)):
        echo "No Google Analytics access token was given.";
    else:
        global $api_key;

        //Get web properties for account
        $url = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles?key='.$api_key;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    endif;
}

function getAccessToken($refresh_token)
{
    if (empty($refresh_token)):
        echo "No Google Analytics refresh token was given.";
    else:
        global $client_id;
        global $client_secret;

        //Get access token using refresh token
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'refresh_token='.$refresh_token.'&client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        return $result['access_token'];
    endif;
}

function setRefreshToken($username, $refresh_token)
{
    if (empty($refresh_token)):
        echo "No Google Analytics refresh token was given.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('ga_refresh_token' => $refresh_token)
            ));
        return true;
    endif;
}

function setWebProperty($username, $ga_property_id, $ga_property_name)
{
    if (empty($ga_property_id)):
        echo "No Google Analytics web property ID was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('ga_web_property' => array('ga_property_id' => $ga_property_id, 'ga_property_name' => $ga_property_name)
            )));
        return true;
    endif;
}

function deleteWebProperty($username, $ga_property_id)
{
    if (empty($ga_property_id)):
        echo "No Google Analytics web property ID was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('ga_web_property' => array('ga_property_id' => $ga_property_id)
            )));
        return true;
    endif;
}

?>