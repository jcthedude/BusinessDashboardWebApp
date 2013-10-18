<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    $twitter_oauth_token = $query['twitter_oauth_token'];
    $twitter_oauth_token_secret = $query['twitter_oauth_token_secret'];

    if(isset($twitter_oauth_token) && isset($twitter_oauth_token_secret)):
        echo "twitter_oauth_token --- " . $twitter_oauth_token;
        echo "twitter_oauth_token_secret --- " . $twitter_oauth_token_secret;
    else:
        header('Location: twitter-login.php');
        exit();
    endif;
endif;

?>