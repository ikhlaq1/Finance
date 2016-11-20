<?php

    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("change_password_form.php", ["title" => "Change Password"]);
    }
    
     // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $hash = CS50::query("SELECT hash FROM users WHERE id = ?", $_SESSION["id"]);
        $old_password_hash = $hash[0]["hash"];
        
        if (password_verify($_POST["old-password"], $old_password_hash) != true)
        {
            apologize("Old password submitted is incorrect.");
        }
        else if ($_POST["new-password"] != $_POST["new-password-confirmation"])
        {
            apologize("New password does not match its confirmation.");
        }
        else
        {
            CS50::query("UPDATE users SET hash = ? WHERE id = ?", password_hash($_POST["new-password"], PASSWORD_DEFAULT), $_SESSION["id"]);
            render("change_password_success.php", ["title" => "Password Change Success"]);
        }
    }
?>