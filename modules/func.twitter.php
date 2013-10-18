<?php

function getOAuthToken($username, $access_token)
{
    if (empty($access_token)):
        echo "No Twitter access token given.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('twitter_oauth_token' => $access_token['oauth_token'], 'twitter_oauth_token_secret' => $access_token['oauth_token_secret'])
            ));
        return true;
    endif;
}

?>