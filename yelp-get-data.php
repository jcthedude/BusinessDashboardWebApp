<?php

include_once('modules/config.php');
include_once('modules/class.oauth.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['yelp_business'])):
        foreach ($query['yelp_business'] as $obj_business):
            $result_yelp = makeYelpAPIRequestBusiness($obj_business['yelp_id']);

            echo "Business: " . $result_yelp['name'] . '<br/>';
            echo "Yelp URL: " . $result_yelp['url'] . '<br/>';
            echo "Claimed: " . $result_yelp['is_claimed'] . '<br/>';
            echo "Rating: " . $result_yelp['rating'] . '<br/>';
            echo "Review Count: " . $result_yelp['review_count'] . '<br/>';
            echo "Most Recent Reviews: " . '<br/>';
            foreach ($result_yelp['reviews'] as $item):
                echo date($date_format, $item['time_created']) . '---' . $item['user']['name'] . '---' . $item['rating'] . '---' . $item['excerpt'] . '<br/>';
            endforeach;
        endforeach;
    endif;
endif;

?>