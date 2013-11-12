<?php
include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_access_token'])):
        $access_token = $query['facebook_access_token'];
    endif;

    $code = $_REQUEST["code"];

    // get user access_token
    if (isset($code)):
        $access_token = getFacebookAccessToken($code);
        setFacebookAccessToken($query['username'], $access_token);
    endif;

    // run fql query
    $fql_query_user = json_decode(getFacebookUser($access_token));
    $fql_query_pages = json_decode(getFacebookPages($access_token));

    //Check for errors
    if ($fql_query_user->error):
        // check to see if this is an oAuth error:
        if ($fql_query_user->error->type== "OAuthException"):
            // Retrieving a valid access token.
            $dialog_url = "https://www.facebook.com/dialog/oauth?"
                . "scope=" . $facebook_scope
                . "&client_id=" . $facebook_app_id
                . "&redirect_uri=" . urlencode($facebook_auth_url);
            echo("<script> top.location.href='" . $dialog_url
                . "'</script>");
        else:
            echo "other error has happened";
        endif;
    else:
        // display results of fql query
        echo '<pre>';
        print_r("User:");
        print_r($fql_query_user);
        echo '</pre>';
        echo "<br><br>";
        echo '<pre>';
        print_r("Pages:");
        print_r($fql_query_pages);
        echo '</pre>';
        echo "<br><br>";
    endif;
endif;

?>