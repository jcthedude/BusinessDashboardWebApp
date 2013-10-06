<?php

include('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $refresh_token = $query['ga_refresh_token'];
    $result_properties = [];

    if (!isset($query['ga_refresh_token'])):
        echo "No Google Analytics refresh token was found.";
        header('Location: members.php');
        exit();
    else:
        //Get access token using refresh token
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'refresh_token='.$refresh_token.'&client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        $access_token = $result['access_token'];

        //Get web properties for account
        if(isset($access_token)):
            $url = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles?key='.$api_key;
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            $result_properties = json_decode($data, true);

            foreach ($result_properties['items'] as $item):
                $dropdown .= "<option value='" . $item['id'] . "'>" . $item['name'] . "</option>";
            endforeach;
        endif;

//        //Get visitors for last 30 days
//        //TODO: Add variables for metrics and dimensions for easier query changes
//        if(isset($access_token)):
//            $endDate = date('Y-m-d');
//            $startDate = date('Y-m-d', strtotime('-30 day'));
//            $total_visitors = 0;
//
//            $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors&dimensions=ga:date&max-results='.$max_results;
//            $ch = curl_init();
//            $timeout = 5;
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//            $data = curl_exec($ch);
//            curl_close($ch);
//            $result_visitors = json_decode($data, true);
//
//            foreach ($result_visitors['rows'] as $item) {
//                $item['date'] = $item[0];
//                unset($item[0]);
//
//                $item['visitors'] = $item[1];
//                unset($item[1]);
//
//                $total_visitors += $item['visitors'];
//
//                echo $item['date'].'---'.$item['visitors']."<br>";
//            }
//
//            echo "Total---".$total_visitors."<br>";
//        endif;
    endif;

    if(isset($_POST['property_select'])):
        $ga_property_id = $_POST['property_select'];
        $ga_property_name = "";

        echo "Selected Web property ID: ".$ga_property_id;

        foreach ($result_properties['items'] as $item):
            if ($item['id'] === $ga_property_id):
                $ga_property_name = $item['name'];
                echo "Selected Web property Name: ".$item['name'];
            endif;
        endforeach;

        getWebProperty($query['username'], $ga_property_id, $ga_property_name);
    endif;
endif;

?>

<html>
<head>
    <title>Select your Web Property</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <select name="property_select">
        <?php echo $dropdown;?>
    </select>
    <input name="submit" type="submit" value="Submit">
</form>
</body>
</html>