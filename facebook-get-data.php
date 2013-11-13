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
            $fql_query_friends = json_decode(getFacebookFriends($access_token, $obj_user['facebook_uid']), true);

            //Check for errors
            if ($fql_query_friends->error):
                if ($fql_query_friends->error->type== "OAuthException"):
                    callFacebookAuth();
                else:
                    echo "Other Facebook authentication error has happened";
                endif;
            endif;

            echo 'Friends: ' . count($fql_query_friends['data']);
            echo '<br><br>';
            echo '<pre>';
            print_r($fql_query_friends);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    endif;

endif;

?>