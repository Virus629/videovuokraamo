<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'movies');
    date_default_timezone_set('Europe/Helsinki');

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if($mysqli == false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }

?>