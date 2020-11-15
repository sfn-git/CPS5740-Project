<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Confirm Order</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <?php
    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged in to search and order an item. <a href='phase2.php'>Click here</a> to go back home.</div>");
    }
    ?>
    <div class='header'>
        <div class='header-text'>Review Order</div>
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
        $product_ids = $_POST["product_id"];
        $order_quantity = $_POST["order_quantity"];
        $available = array();
        $unavailable = array();
        $product_total = 0;

        foreach ($product_ids as $key => $value) {
            $id = $value;
            $quantity = $order_quantity[$key];

            // Checks if user entered a number
            if($quantity <=0){
                continue;
            }

            $sql = "SELECT name, quantity FROM 2020F_nadeems.PRODUCT where product_id=$id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            if($row['quantity'] <=0 || $row['quantity'] < $quantity){
                array_push($unavailable, $row['name']);
                continue;
            }

            $available[$id] = $quantity;
            $product_total += 1;

        }

        foreach ($unavailable as $key => $value) {
            echo "<div class='error-message'>$value is currently out of stock or exceed the available quantity.</div>";
        }

        if($product_total <= 0){
            echo "<div class='error-message'>No items selected. <a href='customer_home.php'>Click here</a> to go back home.</div>";
        }else{
            echo "<div class='message'>Review your order before continuing</div>";
            echo "<form action='customer_order.php' method='POST'>";
            echo "<table>";
            echo "<tr class='table_header'>";
                echo "<th>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "<th>Unit Price</th>";
                echo "<th>Sub total</th>";
            echo "</tr>";
            $total = 0;
            foreach ($available as $key => $value) {
                $sql = "SELECT name, sell_price FROM 2020F_nadeems.PRODUCT where product_id=$key";
                
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $sub_total = $row['sell_price'] * $value;
                $total += $sub_total;
                echo "<tr>";
                    echo "<td><input type='hidden' name='id[$key]' value='$key'>".$row['name']."</td>";
                    echo "<td><input type='hidden' name='quantity[$key]' value='$value'>".$value."</td>";
                    echo "<td>$".$row['sell_price']."</td>";
                    echo "<td>$".$sub_total."</td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td><strong>Total</strong></td>";
                echo "<td style='border-right: 1px solid white;'></td>";
                echo "<td style='border-right: 1px solid white;'></td>";
                echo "<td><strong>$$total</strong></td>";
            echo "</tr>";
            echo "</table>";
            echo "<div class='input-submit'>";
            echo "<input type='submit' style='width: 25vw; margin: auto; margin-top: 10px;' value='Place Order!' >";
            echo "</div>";
            echo "</form>";
        }
        
        mysqli_close($conn);
    ?>
</body>
</html>