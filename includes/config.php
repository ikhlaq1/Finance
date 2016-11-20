<?php

    /**
     * config.php
     *ikhlaq ul firdous
     * Computer Science 50
     * Problem Set 7
     *
     * Configures app.
     */

    ini_set("display_errors", true);
    error_reporting(E_ALL);

    
    require("helpers.php");

    require("../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");

    session_start();

    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/logout.php", "/register.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }

?>
