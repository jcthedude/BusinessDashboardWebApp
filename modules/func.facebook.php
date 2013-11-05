<?php

function getFacebookAccessToken($username, $facebook_access_token)
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