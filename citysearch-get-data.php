<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['citysearch_business'])):
        foreach ($query['citysearch_business'] as $obj_business):
            $result_citysearch = makeCitysearchAPIRequestBusiness($obj_business['citysearch_id']);
            $total_rating = 0;

            echo "Business: " . $obj_business['citysearch_name'] . '<br/>';
            foreach ($result_citysearch['results']['reviews'] as $item):
                $total_rating += $item['review_rating'];
            endforeach;
            echo "Rating: " . $total_rating / count($result_citysearch['results']['reviews']) . '<br/>';
            echo "Review Count: " . count($result_citysearch['results']['reviews']) . '<br/>';
            echo "Most Recent Reviews: " . '<br/>';
            foreach ($result_citysearch['results']['reviews'] as $item):
                $date = strtotime($item['review_date']);
                echo date($date_format, $date) . '---' . $item['review_author'] . '---' . $item['review_rating'] . '---' . $item['review_text'] . $item['review_url']  . '<br/>';
            endforeach;
        endforeach;
    endif;
endif;

?>