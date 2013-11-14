<?php

include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['facebook_access_token'])):
        $access_token = $query['facebook_access_token'];
    else:
        callFacebookAuth();
    endif;

    if(isset($query['facebook_user'])):
        foreach ($query['facebook_user'] as $obj_user):
            //Run fql query
            $fql_query_user = json_decode(getFacebookUserDetails($access_token, $obj_user['facebook_uid']), true);

            //Check for errors
            if ($fql_query_user->error):
                if ($fql_query_user->error->type== "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo '<pre>';
            echo 'User Details';
            echo '<br>';
            print_r($fql_query_user);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    endif;

    if(isset($query['facebook_page'])):
        foreach ($query['facebook_page'] as $obj_page):
            //Run fql query
            $fql_query_page = json_decode(getFacebookPageDetails($access_token, $obj_page['facebook_page_id']), true);

            //Check for errors
            if ($fql_query_page->error):
                if ($fql_query_page->error->type== "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo '<pre>';
            echo 'Page Details:';
            echo '<br>';
            print_r($fql_query_page);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    endif;

endif;

?>