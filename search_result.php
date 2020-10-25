<?php 
    include("dbconfig.php");
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

    $search = mysqli_real_escape_string($conn,$_POST["value"]);
    $sql;
    $execute = true;

    if($search == "*"){
        $sql = "SELECT p.product_id, p.name, p.description, p.cost, p.sell_price, p.quantity, p.vendor_id, v.name AS vName, e.name AS eName FROM 2020F_nadeems.PRODUCT AS p, CPS5740.VENDOR AS v, CPS5740.EMPLOYEE as e WHERE p.vendor_id = v.vendor_id AND p.employee_id = e.employee_id;";
    }else if($search == " "){
        $execute = false;
        echo false;
    }else{
        $sql = "SELECT p.product_id, p.name, p.description, p.cost, p.sell_price, p.quantity, p.vendor_id, v.name AS vName, e.name AS eName FROM 2020F_nadeems.PRODUCT AS p, CPS5740.VENDOR AS v, CPS5740.EMPLOYEE as e WHERE (p.vendor_id = v.vendor_id AND p.employee_id = e.employee_id) AND (p.name LIKE '%$search%' or p.description LIKE '%$search%')";
    }

    if($execute){
        $result = mysqli_query($conn, $sql);
        $send = array();
        $i = 1;
        $num_rows = mysqli_num_rows($result);
        
        if($num_rows  == 0){
            echo false;
        }else{
            echo "{";
                while($row = mysqli_fetch_assoc($result)){
                    echo "\"$i\":";
                    echo json_encode($row);
                    
                    if($i != $num_rows){
                        echo ",";
                    }
                    $i += 1;
                }
                echo "}";
        }
    }

    mysqli_close($conn);

?>