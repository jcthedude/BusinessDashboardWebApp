<?php

include_once('modules/config.php');

if(!loggedIn()):
    echo '<script> window.location="login.php"; </script> ';
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

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

        setRefreshToken($query['username'], $refresh_token);

        echo '<script> window.location="ga-get-profile.php"; </script> ';
    else:
        echo "No Google Analytics code was returned.";
        echo '<script> window.location="dashboard.php"; </script> ';
    endif;
endif;

?>