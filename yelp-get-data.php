<?php

include_once('modules/config.php');
include_once('modules/class.oauth.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['yelp_business'])):
        foreach ($query['yelp_business'] as $obj_business):
            $unsigned_url = "http://api.yelp.com/v2/business/".$obj_business['yelp_id'];

            // Get signed url
            $token = new OAuthToken($yelp_token, $yelp_token_secret);
            $consumer = new OAuthConsumer($yelp_consumer_key, $yelp_consumer_secret);
            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
            $oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);
            $oauthrequest->sign_request($signature_method, $consumer, $token);
            $signed_url = $oauthrequest->to_url();

            // Send Yelp API Call
            $ch = curl_init($signed_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $data = curl_exec($ch);
            curl_close($ch);
            $result_yelp = json_decode($data, true);

            echo "Business: " . $result_yelp['name'] . '<br/>';
            echo "Rating: " . $result_yelp['rating'] . '<br/>';
            echo "Review Count: " . $result_yelp['review_count'] . '<br/>';
            echo "Reviews: " . '<br/>';
            foreach ($result_yelp['reviews'] as $item):
                echo $item['rating'] . '---' . $item['excerpt'] . '<br/>';
            endforeach;
        endforeach;
    endif;
endif;

?>