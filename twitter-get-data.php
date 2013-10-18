<?php

include_once('modules/config.php');
include_once('modules/class.twitter.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $twitter_oauth_token = $query['twitter_oauth_token'];
    $twitter_oauth_token_secret = $query['twitter_oauth_token_secret'];

    if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
        $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_oauth_token, $twitter_oauth_token_secret);
        $content = $connection->get('users/show', array('screen_name' => 'RealRonHoward'));
        var_dump($content);
    else:
        header('Location: twitter-login.php');
        exit();
    endif;
endif;

?>