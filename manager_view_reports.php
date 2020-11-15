<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Report</title>
    <?php include 'libraries.php'?>
</head>
<body>
    


<?php

    if($_COOKIE["is_manager"] == 1 || isset($_COOKIE["is_manager"])){
        include('dbconfig.php');
        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

        $type = $_GET['report-type'];//all-time, past-week, current-month, past-month, past-year
        $timePeriod = $_GET['time-period']; //all, products, vendors
        $range = getRange($timePeriod);
        
        echo "<div class='header'>
            <div class='header-text'>Manager Report</div>
            <div>Report Type: <strong>$type</strong></div>
            <div>Time Period: <strong>$timePeriod</strong></div>
        </div>";
        if($type == "all"){
            
            $sql = 
            "SELECT p.name as `Product Name`, v.name as `Vendor Name`, p.cost as `Unit Cost`, p.quantity as `Current Quantity`, po.quantity as `Sold Quantity`, p.sell_price as `Sold Unit Price`, po.quantity*p.sell_price as `Sub Total`, (p.sell_price-p.cost)*po.quantity as `Profit`, concat(c.first_name,' ',c.last_name) as `Customer Name`, o.date as `Order Date` FROM 2020F_nadeems.PRODUCT p, 2020F_nadeems.PRODUCT_ORDER po, 2020F_nadeems.CUSTOMER c, 2020F_nadeems.`ORDER` o, CPS5740.VENDOR v WHERE p.product_id=po.product_id AND p.vendor_id = v.vendor_id AND o.order_id = po.order_id AND o.customer_id = c.customer_id $range order by o.date desc";
            $result = mysqli_query($conn,$sql);
            echo "<table style='width: 80vw;'>";
                echo "<tr class='table_header'>";
                    echo "<th>Product Name</th>";
                    echo "<th>Vendor Name</th>";
                    echo "<th>Unit Cost</th>";
                    echo "<th>Current Quantity</th>";
                    echo "<th>Sold Quantity</th>";
                    echo "<th>Sold Unit Price</th>";
                    echo "<th>Sub Total</th>";
                    echo "<th>Profit</th>";
                    echo "<th>Customer Name</th>";
                    echo "<th>Order Date</th>";
                echo "</tr>";

            while($row = mysqli_fetch_assoc($result)){

                $productName = $row['Product Name'];
                $vendorName = $row['Vendor Name'];
                $unitCost = $row['Unit Cost'];
                $currQuantity = $row['Current Quantity'];
                $soldQuantity = $row['Sold Quantity'];
                $soldUnitPrice = $row['Sold Unit Price'];
                $subTotal = $row['Sub Total'];
                $profit = $row['Profit'];
                $custName = $row['Customer Name'];
                $date = $row['Order Date'];

                echo "<tr class='table_header'>";
                    echo "<th>$productName</th>";
                    echo "<th>$vendorName</th>";
                    echo "<th>$$unitCost</th>";
                    echo "<th>$currQuantity</th>";
                    echo "<th>$soldQuantity</th>";
                    echo "<th>$$soldUnitPrice</th>";
                    echo "<th>$$subTotal</th>";
                    echo "<th>$$profit</th>";
                    echo "<th>$custName</th>";
                    echo "<th>$date</th>";
                echo "</tr>";
            }
            echo "</table>";
        }elseif($type == "products"){
            $sql = "SELECT p.name as productName, v.name as vendorName, p.cost as cost, p.quantity as quantity, sum(po.quantity) as quantitySold, p.sell_price as sellPrice FROM 2020F_nadeems.PRODUCT_ORDER po, 2020F_nadeems.PRODUCT p, CPS5740.VENDOR v, 2020F_nadeems.`ORDER` o WHERE p.product_id = po.product_id AND p.vendor_id = v.vendor_id AND po.order_id = o.order_id $range GROUP BY po.product_id order by o.date desc";
            
            $result = mysqli_query($conn,$sql);
            echo "<table style='width: 80vw;'>";
                echo "<tr class='table_header'>";
                    echo "<th>Product Name</th>";
                    echo "<th>Vendor Name</th>";
                    echo "<th>Avg Unit Cost</th>";
                    echo "<th>Current Quantity</th>";
                    echo "<th>Sold Quantity</th>";
                    echo "<th>Sold Unit Price</th>";
                    echo "<th>Sub Total</th>";
                    echo "<th>Profit</th>";
                echo "</tr>";

            $subTotal = 0;
            $totalProfit = 0;
            while($row = mysqli_fetch_assoc($result)){

                $productName = $row['productName'];
                $vendorName = $row['vendorName'];
                $unitCost = $row['cost'];
                $currQuantity = $row['quantity'];
                $soldQuantity = $row['quantitySold'];
                $soldUnitPrice = $row['sellPrice'];
                $productSubTotal = $soldUnitPrice * $soldQuantity;
                $productTotalCost = $soldQuantity * $unitCost;
                $profit = $productSubTotal - $productTotalCost;
                $subTotal += $productSubTotal;
                $totalProfit += $profit;

                echo "<tr class='table_header'>";
                    echo "<th>$productName</th>";
                    echo "<th>$vendorName</th>";
                    echo "<th>$$unitCost</th>";
                    echo "<th>$currQuantity</th>";
                    echo "<th>$soldQuantity</th>";
                    echo "<th>$$soldUnitPrice</th>";
                    echo "<th>$$productSubTotal</th>";
                    echo "<th>$$profit</th>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<th><strong>Total</strong></th>";
                echo "<th></th>";
                echo "<th></th>";
                echo "<th></th>";
                echo "<th></th>";
                echo "<th></th>";
                echo "<th>$$subTotal</th>";
                echo "<th>$$totalProfit</th>";
            echo "</tr>";
            echo "</table>";
        }elseif($type == "vendors"){
            $sql = "SELECT v.name as vName, (SELECT SUM(quantity) FROM 2020F_nadeems.PRODUCT WHERE vendor_id = v.vendor_id) as quantityStock,
            SUM(p.cost*po.quantity) as vendorAmount,
            SUM(po.quantity) as soldQuantity,
            SUM(p.sell_price*po.quantity) as subTotal
            FROM CPS5740.VENDOR v, 2020F_nadeems.`ORDER` o, 2020F_nadeems.PRODUCT_ORDER po, 2020F_nadeems.PRODUCT p 
            WHERE o.order_id = po.order_id AND p.product_id = po.product_id AND v.vendor_id = p.vendor_id $range
            GROUP BY v.vendor_id ORDER BY o.date desc";

            $result = mysqli_query($conn,$sql);
            echo "<table style='width: 80vw;'>";
                echo "<tr class='table_header'>";
                    echo "<th>Vendor Name</th>";
                    echo "<th>Quantity in Stock</th>";
                    echo "<th>Amount to Vendor</th>";
                    echo "<th>Sold Quantity</th>";
                    echo "<th>Sub Total Sale</th>";
                    echo "<th>Profit</th>";
                echo "</tr>";

            $subTotal = 0;
            $totalProfit = 0;
            $vendorAmountTotal = 0;
            while($row = mysqli_fetch_assoc($result)){

                $vendorName = $row['vName'];
                $quantityIS = $row['quantityStock'];
                $vendorAmount = $row['vendorAmount'];
                $quantitySold = $row['soldQuantity'];
                $subTotalSale = $row['subTotal'];
                $profit = $subTotalSale - $vendorAmount;
                $subTotal += $subTotalSale;
                $totalProfit += $profit;
                $vendorAmountTotal += $vendorAmount;

                echo "<tr class='table_header'>";
                    echo "<th>$vendorName</th>";
                    echo "<th>$quantityIS</th>";
                    echo "<th>$$vendorAmount</th>";
                    echo "<th>$quantitySold</th>";
                    echo "<th>$$subTotalSale</th>";
                    echo "<th>$$profit</th>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<th><strong>Total</strong></th>";
                echo "<th></th>";
                echo "<th>$$vendorAmountTotal</th>";
                echo "<th></th>";
                echo "<th>$$subTotal</th>";
                echo "<th>$$totalProfit</th>";
            echo "</tr>";
            echo "</table>";
        }else{

        }

        mysqli_close($conn);
    }else{
        die("You must be logged in as a manager.");
    }

//all-time, past-week, current-month, past-month, past-year
function getRange($range){
    if($range == "all-time"){
        return '';
    }elseif($range == "past-week"){
        @$lastWeek = date("Y-m-d", strtotime("-7 days"));
        return "AND o.date BETWEEN '$lastWeek' AND now()";
    }elseif($range == "current-month"){
        @$days = date("d", strtotime("Now")) - 1;
        @$currMonth = date("Y-m-d", strtotime("-$days days"));
        return "AND o.date BETWEEN '$currMonth' AND now()";
    }elseif($range == "past-month"){
        @$lastMonth = date("Y-m-d", strtotime("-1 month"));
        return "AND o.date BETWEEN '$lastMonth' AND now()";
    }elseif($range == "past-year"){
        @$year = date("Y", strtotime("Now"));
        return "AND o.date BETWEEN '$year-01-01' AND now()";
    }else{
        return '';
    }
}
?>

</body>
</html>