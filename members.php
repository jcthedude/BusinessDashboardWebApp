<?php

include_once("modules/config.php");

if(!loggedIn()):
    header('Location: login.php');
    exit();
else:
    print("Welcome to the members page <b>".$_SESSION["username"]."</b><br>\n");
    print("</br><a href=\"logout.php"."\">Logout</a>");
    print("</br><a href=\"password-change.php"."\">Change Password</a>");
    print("</br><a href=\"email-change.php"."\">Change Email Address</a>");
    print("</br><a href=\"ga-get-profile.php"."\">Manage Google Analytics Profile</a>");
    print("</br><a href=\"ga-get-data-month.php"."\">Get Google Analytics Monthly Data</a>");
    print("</br><a href=\"ga-get-data-year.php"."\">Get Google Analytics Yearly Data</a>");
    print("</br><a href=\"facebook-get-user.php"."\">Manage Facebook Account</a>");
    print("</br><a href=\"facebook-get-data.php"."\">Get Facebook Data</a>");
    print("</br><a href=\"yelp-get-business.php"."\">Manage Yelp Business</a>");
    print("</br><a href=\"yelp-get-data.php"."\">Get Yelp Data</a>");
    print("</br><a href=\"places-get-business.php"."\">Manage Google Places Business</a>");
    print("</br><a href=\"places-get-data.php"."\">Get Google Places Data</a>");
    print("</br><a href=\"citysearch-get-business.php"."\">Manage Citysearch Business</a>");
    print("</br><a href=\"citysearch-get-data.php"."\">Get Citysearch Data</a>");
    print("</br><a href=\"twitter-get-user.php"."\">Manage Twitter User</a>");
    print("</br><a href=\"twitter-get-data.php"."\">Get Twitter Data</a>");
    echo '<br>';
    print("</br><a href=\"dev_session-data.php"."\">See Session Data</a>");
    print("</br><a href=\"dev_destroy.php"."\">Destroy Session Data</a>");
    echo '<br><br>';

    try {
        $query = $coll->find();

        // iterate through the result set and print each document
        echo $query->count() . ' document(s) found. <br/>';
        foreach ($query as $obj):
            echo '<pre>';
            print_r($obj);
            echo '</pre>';
            echo '<br><br>';
        endforeach;
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
endif;

?>