<?php

include_once("config.php");
include_once("func.yelp.php");

function getYelpReviewScore()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['yelp_business'])):
        $result_yelp = makeYelpAPIRequestBusiness($query['yelp_business'][0]['yelp_id']);
        return $result_yelp['rating'];
    endif;
}

?>