<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <?php
    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged in to search and order an item. <a href='phase2.php'>Click here</a> to go back home.</div>");
    }
    ?>
    <div class='header'>
        <div class='header-text'>Order Confirmation</div>
    </div>
    <div class='user-link center-link logout' style='margin-top: 10px;'>
        <a href='customer_logout.php'>Customer Logout</a>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='phase2.php'>Project Home</a>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='customer_home.php'>Customer Home</a>
    </div>
    <?php
    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $custID = $_COOKIE["customer_id"];
    $order = $_POST["quantity"];
    $success = true;
    $unsuccessful = array();
    mysqli_query($conn, "START TRANSACTION");

    // Checks if quantity is available for order.
    foreach ($order as $key => $quantity) {
        $pid = $key;
        // Deduct/check quantity in product table
        $sql = "SELECT name, quantity FROM 2020F_nadeems.PRODUCT WHERE product_id = $pid";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];

        if($row['quantity'] <= 0 || $row['quantity'] < $quantity){
            array_push($unsuccessful,"$name is out of stock or exceeds the amount requested!");
            $success = false;
        }else{
            $sql = "UPDATE 2020F_nadeems.PRODUCT SET quantity = quantity - $quantity WHERE product_id = $pid";
            if(!mysqli_query($conn, $sql)){
                array_push($unsuccessful,"Something went wrong processing your order :(");
                mysqli_query($conn, "ROLLBACK");
                $success = false;
            }
        }
    }

    if($success){
        // Create order in orders table
        $sql = "INSERT INTO 2020F_nadeems.`ORDER` (customer_id, date) VALUES ($custID, NOW())";
        
        if(!mysqli_query($conn, $sql)){
            array_push($unsuccessful, "Something went wrong processing your order :(");
            mysqli_query($conn, "ROLLBACK");
            $success = false;
        }else{

            // Grabs the latest ID stored in this session.
            $order_id = mysqli_insert_id($conn);

            // Add order# to product_order table and include the product.
            foreach ($order as $key => $quantity) {
                $sql = "INSERT INTO 2020F_nadeems.PRODUCT_ORDER (order_id, product_id, quantity) VALUES ($order_id, $key, $quantity)";
                if(!mysqli_query($conn, $sql)){
                    array_push($unsuccessful, "Something went wrong processing your order :(");
                   mysqli_query($conn, "ROLLBACK");
                    $success = false;
                }
            }

            if($success){
                // Entire order process was successful
                mysqli_query($conn, "COMMIT");
                echo "<div class='message'>Your order was successful! See below for your details (Order ID: $order_id)</div>";
                echo "<table>";
                echo "<tr class='table_header'>";
                    echo "<th>Product Name</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Unit Price</th>";
                    echo "<th>Sub total</th>";
                echo "</tr>";
                $total = 0;
                $sql = "SELECT p.name as product_name, p.sell_price as cost, po.quantity as quantity FROM 2020F_nadeems.PRODUCT_ORDER as po, 2020F_nadeems.PRODUCT AS p WHERE order_id = $order_id AND p.product_id = po.product_id;";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $name = $row['product_name'];
                    $cost = $row['cost'];
                    $quantity = $row['quantity'];
                    $sub_total = $cost * $quantity;
                    $total += $sub_total;

                    echo "<tr'>";
                    echo "<th>$name</th>";
                    echo "<th>$quantity</th>";
                    echo "<th>$cost</th>";
                    echo "<th>$sub_total</th>";
                    echo "</tr>";
                }
                echo "<tr>";
                    echo "<td><strong>Total</strong></td>";
                    echo "<td style='border-right: 1px solid white;'></td>";
                    echo "<td style='border-right: 1px solid white;'></td>";
                    echo "<td><strong>$$total</strong></td>";
                echo "</tr>";
                echo "</table>";
            }
        }
    }

    if(!$success){
        foreach ($unsuccessful as $key => $message) {
            echo "<div class='error-message'>$message</div>";
            echo "<br>";
        }
    }

    mysqli_close($conn);

?>

</body>
</html>