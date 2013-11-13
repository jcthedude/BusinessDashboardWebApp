<?php
include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    $code = $_REQUEST["code"];

    // get user access_token
    if (isset($code)):
        $access_token = getFacebookAccessToken($code);
        setFacebookAccessToken($query['username'], $access_token);
        header('Location: facebook-get-user.php');
        exit();
    endif;

endif;

?>
