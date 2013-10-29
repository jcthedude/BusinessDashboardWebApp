<?php

function getFacebookAccessToken($username, $facebook_id, $facebook_access_token)
{
    if (empty($facebook_access_token)):
        echo "No Facebook access token was given.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('facebook_id' => $facebook_id, 'facebook_access_token' => $facebook_access_token)
            ));
        return true;
    endif;
}

?>