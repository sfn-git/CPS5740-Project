<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a product</title>
    <?php include 'libraries.php'?>
</head>
<body>
    
    <?php
        if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }
    ?>

    <div class="header">
        <div class="header-text">Add a Product</div>
    </div>
    <div class='user-link center-link logout'><a href='employee_logout.php'>Employee Logout</a></div>
    <div class='user-link center-link' style="margin-top: 3px"><a href='employee_home.php'>Employee Home</a></div>
    <form action="employee_insert_product.php" method="post">
        <div class="input-form">
            <div class="input-item">
                <label for="productName">Product Name:</label>
                <input type="text" name="productName" id="productName" required>
            </div>
            <div class="input-item">
                <label for="description">Description:</label>
                <input type="text" name="description" id="description" required>
            </div>
            <div class="input-item">
                <label for="cost">Cost:</label>
                <input type="number" name="cost" id="cost" min=0 step=".01" required>
            </div>
            <div class="input-item">
                <label for="sellPrice">Sell Price:</label>
                <input type="number" name="sellPrice" id="sellPrice" min=0 step=".01" required>
            </div>
            <div class="input-item">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min=0 required>
            </div>
            <div class="input-item">
                <label for="vendor">Vendor:</label>
                <select name="vendor" id="vendor" required>
                    <?php
                        // Grabbing Vendors from Table
                        include("dbconfig.php");

                        $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
                        $sql = "SELECT * FROM CPS5740.VENDOR";
                        $result = mysqli_query($conn, $sql);

                        while($row = mysqli_fetch_assoc($result)){
                            echo "<option value='" . $row["vendor_id"] ."'>" . $row["name"] . "</option>";
                        }

                        mysqli_close($conn);
                    ?>
                </select>
            </div>
            <div class="input-submit">
                <input type="submit" value="Add Product">
            </div>
        </div>
    </form>

</body>
</html>