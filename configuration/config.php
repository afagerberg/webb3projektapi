<?php

spl_autoload_register(function ($class_name) {
    include 'classes/'. $class_name . '.class.php';
});

    /* DB settings */
    define("DBHOST", "localhost");
    define("DBUSER", "webb3projekt");
    define("DBPASS", "password");
    define("DBDATABASE", "webb3projekt");
?>