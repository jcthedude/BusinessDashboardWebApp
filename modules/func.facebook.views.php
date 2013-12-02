<?php

include_once("config.php");
include_once("func.facebook.php");

function facebookFans()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_page'])):
        if(isset($query['facebook_access_token'])):
            $access_token = $query['facebook_access_token'];
        else:
            callFacebookAuth();
        endif;

        //Run fql and opengraph queries
        $fql_query_page = json_decode(getFacebookPageDetails($access_token, $query['facebook_page'][0]['facebook_page_id']), true);

        //Check for errors
        if ($fql_query_page['error']):
            if ($fql_query_page['error']['type'] == "OAuthException"):
                callFacebookAuth();
            else:
                return "Other Facebook authentication error has happened";
            endif;
        else:
            return $fql_query_page['data'][0]['fql_result_set'][0]['fan_count'];
        endif;
    endif;
}

?>