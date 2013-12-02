<?php

include_once("config.php");
include_once("func.twitter.php");
include_once('class.twitter.php');

function getTwitterFollowers()
{
    global $coll;
    global $twitter_consumer_key;
    global $twitter_consumer_secret;

    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $twitter_oauth_token = $query['twitter_oauth_token'];
    $twitter_oauth_token_secret = $query['twitter_oauth_token_secret'];

    if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
        if(isset($query['twitter_user'])):
            $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
            $content = $connection->get('users/show', array('screen_name' => $query['twitter_user'][0]['screen_name']));

            return $content['followers_count'];
        endif;
    else:
        echo '<script> window.location="twitter-login.php"; </script> ';
    endif;
}

?>