<?php

include_once("config.php");
include_once("func.yelp.php");

function getYelpReviewScore()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['yelp_business'])):
        $result_yelp = makeYelpAPIRequestBusiness($query['yelp_business']['yelp_id']);

        if(isset($result_yelp['rating'])):
            return $result_yelp['rating'];
        endif;
    endif;
}

?>