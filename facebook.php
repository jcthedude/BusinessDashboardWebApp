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
        $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
            . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url)
            . '&client_secret=' . $facebook_app_secret
            . '&code=' . $code;
        $access_token = substr(file_get_contents($token_url), 13);

        // get extended user access_token
        $extended_token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
            . $facebook_app_id
            . '&client_secret=' . $facebook_app_secret
            . '&grant_type=fb_exchange_token'
            . '&fb_exchange_token=' . $access_token;
        $access_token = substr(file_get_contents($extended_token_url), 13);

        getFacebookAccessToken($query['username'], $access_token);
    endif;

    // run fql query
    $fql_query_url = 'https://graph.facebook.com/fql?q='
        . 'SELECT+name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1+=+me())'
        . '&access_token=' . $access_token;
    $fql_query_result = curl_get_file_contents($fql_query_url);
    $fql_query_obj = json_decode($fql_query_result);

    $fql_query_url2 = 'https://graph.facebook.com/fql?q='
        . 'SELECT+name,+page_url,+about+FROM+page+WHERE+page_id+IN+(SELECT+page_id+FROM+page_admin+WHERE+uid+=+me())'
        . '&access_token=' . $access_token;
    $fql_query_result2 = curl_get_file_contents($fql_query_url2);
    $fql_query_obj2 = json_decode($fql_query_result2);

    //Check for errors
    if ($fql_query_obj->error):
        // check to see if this is an oAuth error:
        if ($fql_query_obj->error->type== "OAuthException"):
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
        print_r("Pages:");
        print_r($fql_query_obj2);
        echo '</pre>';
        echo "<br><br>";
        echo '<pre>';
        print_r("Friends:");
        print_r($fql_query_obj);
        echo '</pre>';
        echo "<br><br>";
    endif;
endif;

?>