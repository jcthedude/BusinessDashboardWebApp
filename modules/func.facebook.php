<?php

function callFacebookAuth()
{
    global $facebook_app_id;
    global $facebook_auth_url;
    global $facebook_scope;

    $dialog_url = "https://www.facebook.com/dialog/oauth?"
        . "scope=" . $facebook_scope
        . "&client_id=" . $facebook_app_id
        . "&redirect_uri=" . urlencode($facebook_auth_url);
    echo("<script> top.location.href='" . $dialog_url . "'</script>");

    return true;
}

function setFacebookAccessToken($username, $facebook_access_token)
{
    if (empty($facebook_access_token)):
        echo "No Facebook access token was given.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('facebook_access_token' => $facebook_access_token)
            ));
        return true;
    endif;
}

function getFacebookAccessToken($code)
{
    global $facebook_app_id;
    global $facebook_auth_url;
    global $facebook_app_secret;

    $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
        . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url)
        . '&client_secret=' . $facebook_app_secret
        . '&code=' . $code;
    $access_token = substr(file_get_contents($token_url), 13);

    // get extended user access_token
    $extended_token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
        . $facebook_app_id
        . '&client_secret=' . $facebook_app_secret
        . '&grant_type=fb_exchange_token'
        . '&fb_exchange_token=' . $access_token;
    return substr(file_get_contents($extended_token_url), 13);
}

function setFacebookPage($username, $facebook_page_id, $facebook_name)
{
    if (empty($facebook_page_id)):
        echo "No Facebook page was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('facebook_page' => array('facebook_page_id' => $facebook_page_id, 'facebook_name' => $facebook_name)
            )));
        return true;
    endif;
}

function deleteFacebookPage($username, $facebook_page_id)
{
    if (empty($facebook_page_id)):
        echo "No Facebook page was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('facebook_page' => array('facebook_page_id' => $facebook_page_id)
            )));
        return true;
    endif;
}

function getFacebookPages($access_token)
{
    $fql_query_url = 'https://graph.facebook.com/fql?q='
        . 'SELECT+name,+page_url,+page_id+FROM+page+WHERE+page_id+IN+(SELECT+page_id+FROM+page_admin+WHERE+uid+=+me())'
        . '&access_token=' . $access_token;
    return curl_get_file_contents($fql_query_url);
}

function getFacebookPageDetails($access_token, $facebook_page_id)
{
    $fql_query_url = 'https://graph.facebook.com/fql?q={'
        . '"page_details":"SELECT+name,+app_id,+about,+page_url,+checkins,+fan_count,+new_like_count,+talking_about_count,+were_here_count,+pic_square,+pic_big+FROM+page+WHERE+page_id+=+' . $facebook_page_id . '"}'
        . '&access_token=' . $access_token;
    return curl_get_file_contents($fql_query_url);
}

function getFacebookPageFeed($access_token, $facebook_page_id)
{
    $opengraph_query_url = 'https://graph.facebook.com/' . $facebook_page_id
        . '/feed?access_token=' . $access_token;
    return curl_get_file_contents($opengraph_query_url);
}

function postFacebookPage($message, $access_token, $facebook_page_id)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $facebook_page_id . '/feed?');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'message=' . $message . '&access_token=' . $access_token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($data, true);
    return ($result);
}

// note this wrapper function exists in order to circumvent PHP’s
//strict obeying of HTTP error codes.  In this case, Facebook
//returns error code 400 which PHP obeys and wipes out
//the response.
function curl_get_file_contents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents) return $contents;
    else return FALSE;
}

?>