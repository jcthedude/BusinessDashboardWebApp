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

function setFacebookUser($username, $facebook_uid, $facebook_name)
{
    if (empty($facebook_uid)):
        echo "No Facebook user was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$addToSet' => array('facebook_user' => array('facebook_uid' => $facebook_uid, 'facebook_name' => $facebook_name)
            )));
        return true;
    endif;
}

function setFacebookPage($username, $facebook_page_id, $facebook_name)
{
    if (empty($facebook_page_id)):
        echo "No Facebook page was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$addToSet' => array('facebook_page' => array('facebook_page_id' => $facebook_page_id, 'facebook_name' => $facebook_name)
            )));
        return true;
    endif;
}

function deleteFacebookUser($username, $facebook_username)
{
    if (empty($facebook_username)):
        echo "No Facebook user was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('facebook_user' => array('facebook_username' => $facebook_username)
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

function getFacebookUser($access_token)
{
    $fql_query_url = 'https://graph.facebook.com/fql?q='
        . 'SELECT+first_name,+last_name,+uid+FROM+user+WHERE+uid+=+me()'
        . '&access_token=' . $access_token;
    return curl_get_file_contents($fql_query_url);
}

function getFacebookPages($access_token)
{
    $fql_query_url = 'https://graph.facebook.com/fql?q='
        . 'SELECT+name,+page_url,+page_id+FROM+page+WHERE+page_id+IN+(SELECT+page_id+FROM+page_admin+WHERE+uid+=+me())'
        . '&access_token=' . $access_token;
    return curl_get_file_contents($fql_query_url);
}

function getFacebookFriends($access_token, $facebook_uid)
{
    $fql_query_url = 'https://graph.facebook.com/fql?q='
        . 'SELECT+name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1+=+' . $facebook_uid . ')'
        . '&access_token=' . $access_token;
    return curl_get_file_contents($fql_query_url);
}

// note this wrapper function exists in order to circumvent PHP’s
//strict obeying of HTTP error codes.  In this case, Facebook
//returns error code 400 which PHP obeys and wipes out
//the response.
function curl_get_file_contents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents) return $contents;
    else return FALSE;
}

?>