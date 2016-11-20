<?php

    
    require("../includes/config.php"); 

    $user = CS50::query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    $user[0]["cash"] = desired_decimal_format($user[0]["cash"]);
    
    $rows = CS50::query("SELECT * FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
    
    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $total = $stock["price"] * $row["shares"];
            
            $stock["price"] = desired_decimal_format($stock["price"]);
            $total = desired_decimal_format($total);
            
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"],
                "total" => $total
            ];
        }
    }
    
    render("portfolio.php", ["positions" => $positions, "user" => $user, "title" => "Portfolio"]);
    
?>
