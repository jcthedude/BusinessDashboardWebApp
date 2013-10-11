<?php

include_once("func.user.php");
include_once("func.mail.php");
include_once("func.ga.php");

session_start();

//Email variables
$sendgrid_url               = 'http://sendgrid.com/api/mail.send.json';
$sendgrid_user              = 'azure_fc45a5108bda2f0b000eb55eab20b9ad@azure.com';
$sendgrid_password          = 'qokkhzi1';
$sendgrid_from              = 'justin.campana@gmail.com';

//Google Analytics API variables
$client_id                  = '925815342836.apps.googleusercontent.com';
$client_secret              = '7KqGnzQxESIQb8mB03Ksz2Ef';
$redirect_uri               = 'http://jjcdashboardapp.azurewebsites.net/ga-auth-callback.php';
$api_key                    = 'AIzaSyCKp12gtsmUTAGPIQL0um_zlLgT03CJHg4';
$get_ga_code_url            = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=".$client_id."&redirect_uri=".$redirect_uri."&access_type=offline&scope=https://www.googleapis.com/auth/analytics.readonly";
//$selected_profile           = '69242945';
$max_results                = '100';

//Database connection variables
//$db_host                    = "us-cdbr-azure-west-b.cleardb.com";
//$db_name                    = "jjcdashA6w6NQ9VA";
//$db_user                    = "bc5d4a46d2d096";
//$db_password                = "e773c370";
//$sql_conn                   = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

//Database connection variables
//$db_server                    = "wy8jcz86k9.database.windows.net";
//$db_name                    = "jjcdashboardapp_db";
//$db_user                    = "slampana";
//$db_password                = "Campana1";
//$sql_conn                   = new PDO("sqlsrv:server=$db_server;database=$db_name", $db_user, $db_password);

try
{
    $conn                   = new Mongo('mongodb://slampana:Campana1@ds041167.mongolab.com:41167/jjcdashboardapp_mongo_db');
    $db                     = $conn->jjcdashboardapp_mongo_db;
    $coll                   = $db->users;
}
catch (MongoConnectionException $e)
{
    die('Error connecting to MongoDB server');
}
catch (MongoException $e) {
    die('Error: ' . $e->getMessage());
}

?>
