<?php
include_once("modules/config.php");

//if(!loggedIn()):
//    header('Location: login.php');
//    exit();
//else:
//    $query = $coll->findOne(array('username' => $_SESSION["username"]));
//
//    if(isset($query['facebook_id'])):
//        $access_token = $query['facebook_access_token'];
//
//        // run fql query
//        $fql_query_url = 'https://graph.facebook.com/fql?q='
//            . 'SELECT+name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1+=+me())'
//            . '&access_token=' . $access_token;
//        $fql_query_result = file_get_contents($fql_query_url);
//        $fql_query_obj = json_decode($fql_query_result, true);
//
//        // display results of fql query
//        echo '<pre>';
//        print_r("query results:");
//        print_r($fql_query_obj);
//        echo '</pre>';
//    else:
//        $code = $_REQUEST["code"];
//
//        // auth user
//        if(empty($code)):
//            $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id='
//                . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url) ;
//            echo("<script>top.location.href='" . $dialog_url . "'</script>");
//        endif;
//
//        // get user access_token
//        $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
//            . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url)
//            . '&client_secret=' . $facebook_app_secret
//            . '&code=' . $code;
//
//        // response is of the format "access_token=AAAC..."
//        $access_token = substr(file_get_contents($token_url), 13);
//    endif;
//endif;

//$code = $_REQUEST["code"];
//
////// auth user
////if(empty($code)) {
////    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id='
////        . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url) ;
////    echo("<script>top.location.href='" . $dialog_url . "'</script>");
////}
//
//// get user access_token
//if (isset($code)):
//    $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
//        . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url)
//        . '&client_secret=' . $facebook_app_secret
//        . '&code=' . $code;
//
//    // response is of the format "access_token=AAAC..."
//    $access_token = substr(file_get_contents($token_url), 13);
//endif;
//
//// run fql query
//$fql_query_url = 'https://graph.facebook.com/fql?q='
//    . 'SELECT+name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1+=+me())'
//    . '&access_token=' . $access_token;
//$fql_query_result = curl_get_file_contents($fql_query_url);
//$fql_query_obj = json_decode($fql_query_result);
//
////Check for errors
//if ($fql_query_obj->error) {
//    // check to see if this is an oAuth error:
//    if ($fql_query_obj->error->type== "OAuthException") {
//        // Retrieving a valid access token.
//        $dialog_url = "https://www.facebook.com/dialog/oauth?"
//            . "client_id=" . $facebook_app_id
//            . "&redirect_uri=" . urlencode($facebook_auth_url);
//        echo("<script> top.location.href='" . $dialog_url
//            . "'</script>");
//    }
//    else {
//        echo "other error has happened";
//    }
//}
//else {
//    // success
//    // display results of fql query
//    echo '<pre>';
//    print_r("query results:");
//    print_r($fql_query_obj);
//    echo "<br><br>";
//    print_r("access token:");
//    print_r($access_token);
//    echo "<br><br>";
//    print_r("code:");
//    print_r($code);
//    echo '</pre>';
//}
//
//// note this wrapper function exists in order to circumvent PHP’s
////strict obeying of HTTP error codes.  In this case, Facebook
////returns error code 400 which PHP obeys and wipes out
////the response.
//function curl_get_file_contents($URL) {
//    $c = curl_init();
//    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($c, CURLOPT_URL, $URL);
//    $contents = curl_exec($c);
//    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
//    curl_close($c);
//    if ($contents) return $contents;
//    else return FALSE;
//}
//
$code = $_REQUEST["code"];

// auth user
if(empty($code)) {
    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id='
        . $facebook_app_id . '&redirect_uri=' . urlencode($facebook_auth_url) ;
    echo("<script>top.location.href='" . $dialog_url . "'</script>");
}

// get user access_token
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

$response_params = array();
parse_str($extended_token_url, $response_params);
echo '<pre>';
print_r("response params:");
print_r($response_params);
echo "<br><br>";
//$extended_access_token = $response_params['access_token'];


// run fql query
$fql_query_url = 'https://graph.facebook.com/fql?q='
    . 'SELECT+name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1+=+me())'
    . '&access_token=' . $access_token;
$fql_query_result = file_get_contents($fql_query_url);
$fql_query_obj = json_decode($fql_query_result, true);

// display results of fql query
echo '<pre>';
print_r("query results:");
print_r($fql_query_obj);
echo "<br><br>";
print_r("code:");
print_r($code);
echo "<br><br>";
print_r("access token:");
print_r($access_token);
echo '</pre>';

?>