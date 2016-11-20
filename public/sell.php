<?php

    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $rows = CS50::query("SELECT * FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        
        if (count($rows) == 0)
        {
            apologize("Nothing to sell.");
        }
        else
        {
            render("sell_form.php", ["rows" => $rows, "title" => "Sell"]);
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $shares = CS50::query("SELECT shares FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        $stock = lookup($_POST["symbol"]);
        $total = $stock["price"] * $shares[0]["shares"];
        
        CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $total, $_SESSION["id"]);
        
        CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $stock["symbol"]);
        
        CS50::query("INSERT INTO history (user_id, transaction, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], "SELL", $stock["symbol"], $shares[0]["shares"], $stock["price"]);
        
        redirect("/");
    }
    
?>
