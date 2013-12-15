<?php

function setTwitterUser($username, $screen_name)
{
    if (empty($screen_name)):
        echo "No twitter username was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('twitter_user' => array('screen_name' => $screen_name)
            )));
        return true;
    endif;
}

function deleteTwitterUser($username, $screen_name)
{
    if (empty($screen_name)):
        echo "No twitter username was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$unset' => array('twitter_user' => array('screen_name' => $screen_name)
            )));
        return true;
    endif;
}

function setOAuthToken($username, $access_token)
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