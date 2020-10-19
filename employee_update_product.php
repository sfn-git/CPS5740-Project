<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
</head>
<body>
    
    <?php
        if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }

        $updates = $_POST["update"];

        
    ?>

</body>
</html>