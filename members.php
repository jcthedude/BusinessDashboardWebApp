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
    print("</br><a href=\"$get_ga_code_url"."\">Get Google Analytics Token</a>");
    print("</br><a href=\"ga-get-profiles.php"."\">Manage Google Analytics Profiles</a>");
    print("</br><a href=\"ga-get-data-month.php"."\">Get Google Analytics Monthly Data</a>");
    print("</br><a href=\"ga-get-data-year.php"."\">Get Google Analytics Yearly Data</a>");
    print("</br><a href=\"yelp-get-business.php"."\">Manage Yelp Business</a>");
    print("</br><a href=\"yelp-get-data.php"."\">Get Yelp Data</a>");
    print("</br><a href=\"twitter-get-data.php"."\">Get Twitter Data</a>");
    echo "<br><br>";

    try {
        $query = $coll->find();

        // iterate through the result set and print each document
        echo $query->count() . ' document(s) found. <br/>';
        foreach ($query as $obj):
            echo '_id: ' . $obj['_id'] . '<br/>';
            echo 'username: ' . $obj['username'] . '<br/>';
            echo 'password: ' . $obj['password'] . '<br/>';
            echo 'email: ' . $obj['email'] . '<br/>';
            echo 'type: ' . $obj['type'] . '<br/>';
            echo 'created: ' . $obj['created'] . '<br/>';
            echo 'modified: ' . $obj['modified'] . '<br/>';
            echo 'token: ' . $obj['token'] . '<br/>';
            foreach ($obj['ga_web_property'] as $obj_property):
                echo 'property: ' . $obj_property['ga_property_name'] . '<br/>';
            endforeach;
            echo '<br/>';
        endforeach;
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
endif;

?>