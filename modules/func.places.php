<?php

function setPlacesBusiness($username, $places_id, $places_name)
{
    if (empty($places_id)):
        echo "No Google Places business was given for addition.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$set' => array('places_business' => array('places_id' => $places_id, 'places_name' => $places_name)
            )));
        return true;
    endif;
}

function deletePlacesBusiness($username, $places_id)
{
    if (empty($places_id)):
        echo "No Google Places business was given for deletion.";
    else:
        global $coll;
        $coll->update(array('username' => $username),
            array('$pull' => array('places_business' => array('places_id' => $places_id)
            )));
        return true;
    endif;
}

function makeGeolocationAPIRequest($location)
{
    if (empty($location)):
        echo "No Places location was given.";
    else:
        //Get lat and lon for location
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$location.'&sensor=false';
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);

        $lat = $result['results'][0]['geometry']['location']['lat'];
        $lng = $result['results'][0]['geometry']['location']['lng'];

        return $lat.','.$lng;
    endif;
}

function makePlacesAPIRequestBusiness($places_id)
{
    if (empty($places_id)):
        echo "No Places business was given.";
    else:
        global $api_key;

        //Get business details
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?reference='.$places_id.'&sensor=false&key='.$api_key;
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

function makePlacesAPIRequestSearch($location, $business)
{
    if (empty($location)):
        echo "No Places location was given.";
    else:
        global $api_key;
        global $radius;

        //Get businesses
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$location.'&radius='.$radius.'&keyword='.$business.'&sensor=false&key='.$api_key;
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