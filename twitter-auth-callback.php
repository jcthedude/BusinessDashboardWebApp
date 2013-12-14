<?php

include_once('modules/config.php');
include_once('modules/class.twitter.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    /* If the oauth_token is old redirect to the connect page. */
    if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']):
        $_SESSION['oauth_status'] = 'oldtoken';
        header('Location: twitter-login.php');
    endif;

    /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
    $connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    /* Request access tokens from twitter */
    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

    /* Save the access tokens*/
    $query = $coll->findOne(array('username' => $_SESSION["username"]));
    setOAuthToken($query['username'], $access_token);

    /* Remove no longer needed request tokens */
    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);

    /* If HTTP response is 200 continue otherwise send to connect page to retry */
    if (200 == $connection->http_code):
        /* The user has been verified and the access tokens can be saved for future use */
        header('Location: twitter-get-data.php');
        exit();
    else:
        header('Location: twitter-login.php');
        exit();
    endif;
endif;

?>
