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

        //Get visitors for last 30 days
        //TODO: Add variables for metrics and dimensions for easier query changes
        if(isset($access_token)):
            foreach ($query['ga_web_property'] as $obj_property):
                $selected_profile = $obj_property['ga_property_id'];
                $endDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime('-365 day'));
                $total_visitors = 0;
                $total_new_visits = 0;

                $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors,ga:newVisits&dimensions=ga:year,ga:month&max-results='.$max_results;
                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $data = curl_exec($ch);
                curl_close($ch);
                $result_visitors = json_decode($data, true);

                echo "Property: " . $obj_property['ga_property_name'] . '<br/>';
                foreach ($result_visitors['rows'] as $item):
                    $item['year'] = $item[0];
                    unset($item[0]);

                    $item['month'] = $item[1];
                    unset($item[1]);

                    $item['visitors'] = $item[2];
                    unset($item[2]);

                    $item['new_visits'] = $item[3];
                    unset($item[3]);

                    $total_visitors += $item['visitors'];
                    $total_new_visits += $item['new_visits'];

                    echo $item['year'].'---'.$item['month'].'---'.$item['visitors'].'---'.$item['new_visits']."<br>";
                endforeach;

                echo "Total---".$total_visitors."<br>";
                echo "Total New Visits---".$total_new_visits."<br>";

                $url = 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$selected_profile.'&start-date='.$startDate.'&end-date='.$endDate.'&metrics=ga:visitors&dimensions=ga:source&sort=-ga:visitors&max-results='.$max_results;
                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $data = curl_exec($ch);
                curl_close($ch);
                $result_sources = json_decode($data, true);

                echo "Property: " . $obj_property['ga_property_name'] . '<br/>';
                foreach ($result_sources['rows'] as $item):
                    $item['source'] = $item[0];
                    unset($item[0]);

                    $item['visitors'] = $item[1];
                    unset($item[1]);

                    echo $item['source'].'---'.$item['visitors']."<br>";
                endforeach;
            endforeach;
        else:
            echo "No Google Analytics access token was found.";
        endif;
    endif;
endif;

?>