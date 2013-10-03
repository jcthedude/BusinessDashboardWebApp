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
    echo "<br><br>";

    try {
        $query = $coll->find();

        // iterate through the result set and print each document
        echo $query->count() . ' document(s) found. <br/>';
        foreach ($query as $obj) {
            echo '_id: ' . $obj['_id'] . '<br/>';
            echo 'username: ' . $obj['username'] . '<br/>';
            echo 'password: ' . $obj['password'] . '<br/>';
            echo 'email: ' . $obj['email'] . '<br/>';
            echo 'type: ' . $obj['type'] . '<br/>';
            echo 'created: ' . $obj['created'] . '<br/>';
            echo 'modified: ' . $obj['modified'] . '<br/>';
            echo 'token: ' . $obj['token'] . '<br/>';
            echo '<br/>';
        }
    } catch (MongoConnectionException $e) {
        die('Error connecting to MongoDB server');
    } catch (MongoException $e) {
        die('Error: ' . $e->getMessage());
    }
endif;

?>