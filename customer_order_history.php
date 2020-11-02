<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <?php
    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged in to view your order history. <a href='phase2.php'>Click here</a> to go back home.</div>");
    }
    ?>

    <div class='header'>
        <div class='header-text'>Your order history.</div>
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
    <br>
    <?php

        include('dbconfig.php');
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
        $custID = $_COOKIE["customer_id"];

        $sql = "SELECT * from 2020F_nadeems.`ORDER` WHERE customer_id=$custID";
        $result = mysqli_query($conn, $sql);
        $grand_total = 0;
        while($row = mysqli_fetch_assoc($result)){
            echo "<table>";
                echo "<tr class='table_header'>";
                    echo "<th>Order ID</th>";
                    echo "<th>Product Name</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Unit Price</th>";
                    echo "<th>Sub total</th>";
                    echo "<th>Date Ordered</th>";
                echo "</tr>";

            $total = 0;
            $order_id = $row['order_id'];
            $date = $row['date'];
            $sql = "SELECT p.name, po.quantity, p.sell_price FROM 2020F_nadeems.PRODUCT as p, 2020F_nadeems.PRODUCT_ORDER as po WHERE po.order_id = $order_id AND po.product_id=p.product_id GROUP BY p.name";
            $poResult = mysqli_query($conn, $sql);
            
            while($poRow = mysqli_fetch_assoc($poResult)){
                $name = $poRow['name'];
                $quantity = $poRow['quantity'];
                $cost = $poRow['sell_price'];
                $sub_total = $cost * $quantity;
                $total += $sub_total;

                echo "<tr>";
                    echo "<th>$order_id</th>";
                    echo "<th>$name</th>";
                    echo "<th>$quantity</th>";
                    echo "<th>$cost</th>";
                    echo "<th>$sub_total</th>";
                    echo "<th>$date</th>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td><strong>Total</strong></td>";
                echo "<td style='border-right: 1px solid white;'></td>";
                echo "<td style='border-right: 1px solid white;'></td>";
                echo "<td style='border-right: 1px solid white;'></td>";
                echo "<td style='border-right: 1px solid black;'></td>";
                echo "<td style='border-left: 1px solid black; text-align: center;'><strong>$$total</strong></td>";
            echo "</tr>";
            echo "</table>";
            echo "<br>";
            echo "<br>";
            $grand_total += $total;
        }
        echo "<table>";
                echo "<tr>";
                    echo "<th>Total Spent</th>";
                    echo "<th>$$grand_total</th>";
                echo "</tr>";
        echo "</table>";
        mysqli_close($conn);
    ?>
</body>
</html>