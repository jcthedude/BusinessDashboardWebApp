<?php

include_once("config.php");
include_once("func.citysearch.php");

function getCitysearchReviewScore()
{
    global $coll;
    $query = $coll->findOne(array('username' => $_SESSION["username"]));

    if(isset($query['citysearch_business'])):
        $result_citysearch = makeCitysearchAPIRequestBusiness($query['citysearch_business'][0]['citysearch_id']);
        $total_rating = 0;

        foreach ($result_citysearch['results']['reviews'] as $item):
            $total_rating += $item['review_rating'];
        endforeach;

        if($total_rating == 0):
            return null;
        else:
            return $total_rating / count($result_citysearch['results']['reviews']);
        endif;
    endif;
}

?>