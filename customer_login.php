<?php 
    if(isset($_COOKIE["customer_id"])){
        header("Location: customer_home.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <div class="header">
        <div class="header-text">Customer Login</div>
    </div>
    <br>
    <form action="customer_home.php" method="POST">
        <div class="input-form">
            <div class="input-item">
                <label for="username">Username: </label>
                <br>
                <input type="text" id="username" name="username" require>
            </div>
            <div class="input-item">
                <label for="password">Password: </label>
                <br>
                <input type="password" id="password" name="password" require>
            </div>
            <div class="input-submit">
                <input type="submit" value="Login!">
            </div>
        </div>
    </form>

</body>
</html>