<?php

include_once("func.user.php");
include_once("func.mail.php");
include_once("func.ga.php");
include_once("func.facebook.php");
include_once("func.places.php");
include_once("func.yelp.php");
include_once("func.twitter.php");
include_once("func.citysearch.php");

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

//General variables
$date_format                    = "n/j/y";

//Email variables
$sendgrid_url                   = 'http://sendgrid.com/api/mail.send.json';
$sendgrid_user                  = 'azure_fc45a5108bda2f0b000eb55eab20b9ad@azure.com';
$sendgrid_password              = 'qokkhzi1';
$sendgrid_from                  = 'justin.campana@gmail.com';

//Google Analytics and Places API variables
$client_id                      = '925815342836.apps.googleusercontent.com';
$client_secret                  = '7KqGnzQxESIQb8mB03Ksz2Ef';
$redirect_uri                   = 'http://localhost:8888/business_dashboard_app/ga-auth-callback.php';
$api_key                        = 'AIzaSyCKp12gtsmUTAGPIQL0um_zlLgT03CJHg4';
$get_ga_code_url                = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=".$client_id."&redirect_uri=".$redirect_uri."&access_type=offline&scope=https://www.googleapis.com/auth/analytics.readonly";
$max_results                    = '100';
$radius                         = '30000';

//Facebook API variables
$facebook_app_id                = '321008764707304';
$facebook_app_secret            = '4f106746a8187a69eef790a5fe9211c2';
$facebook_auth_url              = 'http://localhost:8888/business_dashboard_app/facebook2.php';

//Yelp API variables
$yelp_consumer_key              = 'IFYYmtux295mKmUuScIHbA';
$yelp_consumer_secret           = 'Q1hXFNjRosU54Ovu7JsWtmj_znI';
$yelp_token                     = 'vcILLzXQC39poety7Hqnp4uwLezh-mP5';
$yelp_token_secret              = 'Gy655YMyu-Hc7MEbmkBYT9EP61c';

//Twitter API variables
$twitter_consumer_key           = '40TEYkDzTwH9trC3c8IA';
$twitter_consumer_secret        = 'mW1u76Bz1c5PoMLW08CEKelQa4ejRqzNMbwbh2r9uLk';
$twitter_callback               = 'http://localhost:8888/business_dashboard_app/twitter-auth-callback.php';

//CitySearch API variables
$citysearch_publisher           = '10000005389';

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
