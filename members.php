<?php

include_once("config.php");

print("Welcome to the members page <b>".$_SESSION["username"]."</b><br>\n");
print("<a href=\"logout.php"."\">Logout</a>");
echo "<br><br>";

try {
    $query = $coll->find();

    // iterate through the result set and print each document
    echo $query->count() . ' document(s) found. <br/>';
    foreach ($query as $obj) {
        echo 'username: ' . $obj['username'] . '<br/>';
        echo 'password: ' . $obj['password'] . '<br/>';
        echo 'email: ' . $obj['email'] . '<br/>';
        echo 'type: ' . $obj['type'] . '<br/>';
        echo 'created: ' . $obj['created'] . '<br/>';
        echo '<br/>';
    }

    // disconnect from server
    $conn->close();
} catch (MongoConnectionException $e) {
    die('Error connecting to MongoDB server');
} catch (MongoException $e) {
    die('Error: ' . $e->getMessage());
}