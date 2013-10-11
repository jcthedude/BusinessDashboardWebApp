<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $refresh_token = $query['ga_refresh_token'];

    //Get refresh token
    if(isset($_GET['code'])):
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'code='.$_GET['code'].'&client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri='.$redirect_uri.'&grant_type=authorization_code');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        $refresh_token = $result['refresh_token'];

        getRefreshToken($query['username'], $refresh_token);

        header('Location: ga-get-profiles.php');
        exit();
    else:
        echo "No Google Analytics code was returned.";
        header('Location: members.php');
        exit();
    endif;
endif;

?>