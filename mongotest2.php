<?php

include 'config.php';

try {
    $collection_name = "users";

    // access collection
    $collection = $db->$collection_name;

    // execute query and retrieve all documents
    $cursor = $collection->find();

    // iterate through the result set and print each document
    echo $cursor->count() . ' document(s) found. <br/>';
    foreach ($cursor as $obj) {
        echo 'username: ' . $obj['username'] . '<br/>';
        echo 'password: ' . $obj['password'] . '<br/>';
        echo 'email: ' . $obj['email'] . '<br/>';
        echo 'created: ' . $obj['created'] . '<br/>';
        echo '<br/>';
    }

    // disconnect from server
    $connection->close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server');
} catch (MongoException $e) {
    die('Error: ' . $e->getMessage());
}
