<?php

function getYelpBusiness($username, $yelp_id, $yelp_name)
{
    if (empty($yelp_id)):
        echo "No Yelp business was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$addToSet' => array('yelp_business' => array('yelp_id' => $yelp_id, 'yelp_name' => $yelp_name)
            )));
        return true;
    endif;
}

function deleteYelpBusiness($username, $yelp_id)
{
    if (empty($yelp_id)):
        echo "No Yelp business was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('yelp_business' => array('yelp_id' => $yelp_id)
            )));
        return true;
    endif;
}

function makeYelpAPIRequestBusiness($yelp_id)
{
    if (empty($yelp_id)):
        echo "No Yelp business ID was given.";
    else:
        global $yelp_token;
        global $yelp_token_secret;
        global $yelp_consumer_key;
        global $yelp_consumer_secret;

        $unsigned_url = "http://api.yelp.com/v2/business/" . $yelp_id;

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
        return json_decode($data, true);
    endif;
}

function makeYelpAPIRequestSearch($location, $business)
{
    global $yelp_token;
    global $yelp_token_secret;
    global $yelp_consumer_key;
    global $yelp_consumer_secret;

    $unsigned_url = "http://api.yelp.com/v2/search?term=".$business."&location=".$location;

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
    return json_decode($data, true);
}