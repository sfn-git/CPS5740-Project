<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <?php include 'libraries.php'?>
</head>
<body>
    <h1>Employee Login</h1>

    <form action="employee_home.php" method="POST">
        <label for="username">Username: </label>
        <input type="text" id="username" name="username" require>
        <br>
        <label for="password">Password: </label>
        <input type="password" id="password" name="password" require>
        <br>
        <button type="submit">Login!</button>

    </form>

</body>
</html>