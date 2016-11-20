<?php

    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("buy_form.php", ["title" => "Buy"]);
    }
    
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $stock = lookup($_POST["symbol"]);
        
        if ($stock == false)
        {
            apologize("The requested stock does not exist.");
        }
        
        else if (preg_match("/^\d+$/", $_POST["shares"]) != true)
        {
            apologize("You can only buy whole shares of stocks, not fractions thereof.");
        }
        else
        {
            $total = $stock["price"] * $_POST["shares"];
            
            $cash = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
            
            if ($cash[0]["cash"] < $total)
            {
                apologize("You don't have enough cash to buy the requested amount of stock.");
            }
            else
            {
                CS50::query("INSERT INTO portfolios (user_id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)", $_SESSION["id"], $stock["symbol"], $_POST["shares"]);
                
                CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?", $total, $_SESSION["id"]);
                
                CS50::query("INSERT INTO history (user_id, transaction, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], "BUY", $stock["symbol"], $_POST["shares"], $stock["price"]);
                
                redirect("/");
            }
        }
    }
?>