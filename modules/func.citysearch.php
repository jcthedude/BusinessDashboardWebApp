<?php

function setCitysearchBusiness($username, $citysearch_id, $citysearch_name)
{
    if (empty($citysearch_id)):
        echo "No Citysearch business was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('set' => array('citysearch_business' => array('citysearch_id' => $citysearch_id, 'citysearch_name' => $citysearch_name)
            )));
        return true;
    endif;
}

function deleteCitysearchBusiness($username, $citysearch_id)
{
    if (empty($citysearch_id)):
        echo "No Citysearch business was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('citysearch_business' => array('citysearch_id' => $citysearch_id)
            )));
        return true;
    endif;
}

function makeCitysearchAPIRequestBusiness($citysearch_id)
{
    if (empty($citysearch_id)):
        echo "No Citysearch business was given.";
    else:
        global $citysearch_publisher;

        //Get business details
        $url = 'http://api.citygridmedia.com/content/reviews/v2/search/where?listing_id='.$citysearch_id.'&sort=createdate&format=json&publisher='.$citysearch_publisher;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    endif;
}

function makeCitysearchAPIRequestSearch($location, $business)
{
    if (empty($location)):
        echo "No Citysearch location was given.";
    else:
        global $citysearch_publisher;

        //Get businesses
        $url = 'http://api.citygridmedia.com/content/places/v2/search/where?what='.$business.'&where='.$location.'&format=json&publisher='.$citysearch_publisher;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, true);
    endif;
}