<?php

    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("quote_form.php", ["title" => "Get Quote"]);
    }
    
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $stock = lookup($_POST["symbol"]);
        
        if ($stock != false)
        {
            $stock["price"] = desired_decimal_format($stock["price"]);
            render("quote.php", ["title" => "Quote", "stock" => $stock]);
        }
        else
        {
            apologize("Stock does not exist.");
        }
    }

?>