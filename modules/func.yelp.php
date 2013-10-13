<?php

function getYelpBusiness($username, $yelp_id, $yelp_name)
{
    if (empty($yelp_id)):
        echo "No Yelp business was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$addToSet' => array('yelp_business' => array('yelp_id' => $yelp_id, 'yelp_name' => $yelp_name)
            )));
        return true;
    endif;
}

function deleteYelpBusiness($username, $yelp_id)
{
    if (empty($yelp_id)):
        echo "No yelp business was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('yelp_business' => array('yelp_id' => $yelp_id)
            )));
        return true;
    endif;
}