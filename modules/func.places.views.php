<?php

include_once("config.php");
include_once("func.places.php");

function getPlacesReviewScore()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['places_business'])):
        $result_places = makePlacesAPIRequestBusiness($query['places_business'][0]['places_id']);

        if(isset($result_places['result']['rating'])):
            return $result_places['result']['rating'];
        endif;
    endif;
}

?>