<?php
include_once("modules/config.php");

$app_id = $facebook_app_id;
$app_secret = $facebook_app_secret;
$my_url = $facebook_auth_url;

$access_token = 'CAAEj9K1j4egBAHCZCViPkE7pfAF71ZAjYr2u10jxJZAX5GjDdl4f5omZAY7SfdUCfSQKiPKJU9UxCz46cdN4eZAA4tBKHb808GMe25MCiJ2sizdXwPqiA3jedQTxSk3bJK8dPGkRn4n9kKGSZBrRIUNLYOkBOgmZCsfo6NNDlZChA0E3dtZCO2TCpelbQ0vrpNgEZD';

//// auth user
//if(empty($code)) {
//    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id='
//        . $app_id . '&redirect_uri=' . urlencode($my_url) ;
//    echo("<script>top.location.href='" . $dialog_url . "'</script>");
//}
//
//// get user access_token
//$token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
//    . $app_id . '&redirect_uri=' . urlencode($my_url)
//    . '&client_secret=' . $app_secret
//    . '&code=' . $code;
//
//// response is of the format "access_token=AAAC..."
//$access_token = substr(file_get_contents($token_url), 13);

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
echo '</pre>';

?>