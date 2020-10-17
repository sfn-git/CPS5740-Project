<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log out</title>
    <?php include 'libraries.php'?>
</head>
<body>
    

<?php

setcookie("employee_id", "", time() - (86400*30), "/");
setcookie("is_manager", "", time() - (86400*30), "/");
echo "<div class='message'>Successfully Logged out. <a href='index.php'>Click here</a> to go back home.</div>"

?>
</body>
</html>