<?php
/* DATABASE CONFIGURATION */

// SERVER CONFIGURATION 
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'u159993157_admin');
// define('DB_PASSWORD', 'u^4!SiEW');
// define('DB_DATABASE', 'u159993157_innvestock');
// define("BASE_URL", "https://innvestock.com/admin/");

// LOCALHOST CONFIGURATION
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'innvestock');
define("BASE_URL", "http://localhost/INNVESTOCK/");
// define("BASE_URL", "http://localhost/programs/APP_WEB/INNVESTOCK/");

date_default_timezone_set('America/Bogota');


function getDB()
{
    $dbhost = DB_SERVER;
    $dbuser = DB_USERNAME;
    $dbpass = DB_PASSWORD;
    $dbname = DB_DATABASE;

    try {
        $dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $dbConnection->exec("set names utf8");
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
