<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include 'libraries.php'?>
</head>
<body>

    <?php
        if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }

        include('dbconfig.php');
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

        // Escapes front-end data to prevent injection.
        $product_items = array();
        @$product_items["description"] = mysqli_real_escape_string($conn, $_POST["description"]);
        @$product_items["name"] = mysqli_real_escape_string($conn, $_POST["productName"]);
        @$product_items["vendor_id"] = mysqli_real_escape_string($conn, $_POST["vendor"]);
        @$product_items["cost"] = mysqli_real_escape_string($conn, $_POST["cost"]);
        @$product_items["sell_price"] = mysqli_real_escape_string($conn, $_POST["sellPrice"]);
        @$product_items["quantity"] = mysqli_real_escape_string($conn, $_POST["quantity"]);
        @$product_items["employee_id"] = $_COOKIE["employee_id"];

        // Checks
        // Checks if any field is blank or if # are negative
        foreach($product_items as $key => $value){
            if($value == ""){
                mysqli_close($conn);
                die("<div class='error-message'>".$key." is empty. <a href='employee_add_product.php'>Click here</a> try again</div></div>");
            }

            if(is_numeric($value) && $value < 0){
                mysqli_close($conn);
                die("<div class='error-message'> $key must be greater than 0. <a href='employee_add_product.php'>Click here</a> try again</div></div>");
            }

        }

        // Checks if sell price is less than cost
        if($product_items["cost"] > $product_items["sell_price"]){
            mysqli_close($conn);
            die("<div class='error-message'>Sell price must be higher than the cost. <a href='employee_add_product.php'>Click here</a> to try again.</div>");
        }

        // Checks for duplicate values
        $name = $product_items['name'];
        $sql = "SELECT name FROM 2020F_nadeems.PRODUCT WHERE name='$name'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            mysqli_close($conn);
            die("<div class='error-message'>Product ($name) already exist. <a href='employee_add_product.php'>Click here</a> to try again.</div>");
        }

        // Passes all checks Inserts into DB
        $sql = "INSERT INTO 2020F_nadeems.PRODUCT (description, name, vendor_id, cost, sell_price, quantity, employee_id) VALUES ('". $product_items["description"] ."','". $product_items["name"]."',". $product_items["vendor_id"].",". $product_items["cost"].",". $product_items["sell_price"].",". $product_items["quantity"].",". $product_items["employee_id"].")";

        if(mysqli_query($conn, $sql)){
            echo "<div class='message'>$name was successfully added as a product! <a href='employee_home.php'>Click here</a> to go back home</div>";
        }else{
            echo "<div class='error-message'>Something went wrong! <a href='employee_add_product.php'>Click here</a> try again</div>";
            echo "<div class='error-message'>".mysqli_error($conn)."</div>";
        }

        mysqli_close($conn);
    ?>


    
</body>
</html>