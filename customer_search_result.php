<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Search Result</title>
    <?php include 'libraries.php'?>
</head>
<body>

<?php
    if(!isset($_COOKIE["customer_id"])){
        die("<div class='error-message'>You must be logged in to search and order an item. <a href='phase2.php'>Click here</a> to go back home.</div>");
    }
    ?>
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
    include("dbconfig.php");
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("<div class='error-message'>Unable to connect to database. Try again later</div>");

    $search = mysqli_real_escape_string($conn,$_GET["search"]);
    $sql;
    $execute = true;

    if($search == "*"){
        $sql = "SELECT p.product_id, p.name, p.description, p.sell_price, p.quantity, p.vendor_id, v.name AS vName FROM 2020F_nadeems.PRODUCT AS p, CPS5740.VENDOR AS v WHERE p.vendor_id = v.vendor_id";
    }else if($search == ""){
        $execute = false;
    }else{
        $sql = "SELECT p.product_id, p.name, p.description, p.sell_price, p.quantity, p.vendor_id, v.name AS vName FROM 2020F_nadeems.PRODUCT AS p, CPS5740.VENDOR AS v WHERE p.vendor_id = v.vendor_id AND (p.name LIKE '%$search%' or p.description LIKE '%$search%')";
    }

    if($execute){
        $result = mysqli_query($conn, $sql);
        $send = array();
        $i = 1;
        $num_rows = mysqli_num_rows($result);

        if($num_rows  == 0){
            echo "<div class='error-message'>No search results for $search. <a href='customer_home.php'>Click here</a> to go back home.</div>";
        }else{
            echo "<div class='header'>";
                echo "<div class='header-text'>Search results for <strong>$search<strong></div>";
            echo "</div>";
            echo "<div class='center-text' style='margin-bottom: 5px;'>Enter a quantity for items you would like to buy.</div>";
            echo "<table>";
            echo "<tr id='inner_edit_table' class='table_header'>";
                echo "<th>Product Name</th>";
                echo "<th>Description</th>";
                echo "<th>Price</th>";
                echo "<th>Available Quantity</th>";
                echo "<th>Order Quantity</th>";
                echo "<th>Vendor Name</th>";
            echo "</tr>";
            $item_num = 1;
            echo "<form action='customer_confirm_order.php' method='POST'>";
            while($row = mysqli_fetch_assoc($result)){
                $pid = $row['product_id'];
                $quantity = $row['quantity'];
                echo "<tr>";
                    echo "<th>". $row['name'] . "</th>";
                    echo "<th>". $row['description'] . "</th>";
                    echo "<th>". $row['sell_price'] . "</th>";
                    echo "<th>". $row['quantity'] . "</th>";
                    echo "<th>";
                        echo "<input type='hidden' name='product_id[$item_num]' value='$pid'>";
                        echo "<input type='number' min=0 max=$quantity name='order_quantity[$item_num]'>";
                    echo "</th>";
                    echo "<th>". $row['vName'] . "</th>";
                echo "</tr>";
                $item_num++;
            }
            echo "</table>";
            echo "<div class='input-submit'>";
                echo "<input type='submit' value='Checkout!' style='width: 25vw; margin: auto; margin-top: 15px;'>";
            echo "</div></form>";
        }
    }

    mysqli_close($conn);

?>
</body>
</html>
