<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <div class="header">
        <div class="header-text">Product update result</div>
    </div>
    <?php
        if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }

        include('dbconfig.php');

        $employeeID = $_COOKIE["employee_id"];
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
        $updates = $_POST["update"];

        foreach ($updates as $key => $value) {

            $update = array();
            $productID = mysqli_real_escape_string($conn, $value["product_id"]);
            $update["name"] = mysqli_real_escape_string($conn, $value["product_name"]);
            $update["description"] = mysqli_real_escape_string($conn, $value["description"]);
            $update["cost"] = (float) mysqli_real_escape_string($conn, $value["cost"]);
            $update["sell_price"] = (float) mysqli_real_escape_string($conn, $value["sell_price"]);
            $update["quantity"] = (float) mysqli_real_escape_string($conn, $value["quantity"]);
            $update["vendor_id"] = (int) mysqli_real_escape_string($conn, $value["vendor"]);

            // Checks for correct input
            $productName = $update["name"];
            $continueLoop = false;
            foreach ($update as $key => $value) {
                if($value == "" || $value == NULL){
                    echo "<div class='message error'>Could not update product ID: $productID ($key is blank).</div>";
                    $continueLoop = true;
                    break;
                }
            }

            if($update["cost"] > $update["sell_price"]){
                
                echo "<div class='message error'>Sell price for $productName must be higher than cost.</div>";
                $continueLoop = true;
            }

            if($update["cost"] < 0){
                echo "<div class='message error'>Cost must be greater than 0 for $productName.</div>";
                $continueLoop = true;
            }

            if($update["sell_price"] < 0){
                echo "<div class='message error'>Sell price must be greater than 0 for $productName.</div>";
                $continueLoop = true;
            }

            if($update["quantity"] < 0){
                echo "<div class='message error'>Quantity must be greater than 0 for $productName.</div>";
                $continueLoop = true;
            }

            if($continueLoop){
                continue;
            }

            // Checks to see if there are any changes
            $sql = "SELECT * FROM 2020F_nadeems.PRODUCT WHERE product_id=$productID";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $continueLoop = true;
            foreach($update as $key => $value){
                if($value != $row[$key]){
                    $continueLoop = false;
                    break;
                }
            }

            if($continueLoop){
                echo "<div class='message'>No changes made to $productName. (Product ID: $productID)</div>";
                continue;
            }

            // End of checks. Code pass this will only run if all checks have passed.
            $successful = true;
            foreach ($update as $key => $value) {
                $sql;
                if(gettype($value) != "string"){
                    $sql = "UPDATE 2020F_nadeems.PRODUCT SET $key=$value, employee_id=$employeeID WHERE product_id=$productID";
                }else{
                    $sql = "UPDATE 2020F_nadeems.PRODUCT SET $key=\"$value\", employee_id=$employeeID WHERE product_id=$productID";
                }

                if(!mysqli_query($conn, $sql)){
                    $successful = false;
                }
            }

            echo "<div class='message success'>$productName has been successfully updated! (Product ID: $productID)</div>";

        }
        mysqli_close($conn);
    ?>

    <div class='user-link center-link' style='text-align: center; margin-bottom: 10px; margin-top: 10px;'>
        <a href='employee_search_update.php'>Search and Update Another Product</a>
    </div>
    <div class='user-link center-link' style='text-align: center; margin-bottom: 10px; margin-top: 10px;'>
        <a href='employee_home.php'>Go to Employee Home</a>
    </div>

</body>
</html>