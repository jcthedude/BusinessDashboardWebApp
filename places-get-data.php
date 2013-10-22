<?php

include_once('modules/config.php');

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['places_business'])):
        foreach ($query['places_business'] as $obj_business):
            $result_places = makePlacesAPIRequestBusiness($obj_business['places_id']);

            echo "Business: " . $result_places['result']['name'] . '<br/>';
            echo "Google Places URL: " . $result_places['result']['url'] . '<br/>';
            echo "Rating: " . $result_places['result']['rating'] . '<br/>';
            echo "Review Count: " . count($result_places['result']['reviews']) . '<br/>';
            echo "Most Recent Reviews: " . '<br/>';
            foreach ($result_places['result']['reviews'] as $item):
                echo date($date_format, $item['time']) . '---' . $item['author_name'] . '---' . $item['aspects']['rating'] . '---' . $item['text'] . '<br/>';
            endforeach;
        endforeach;
    endif;
endif;

?>