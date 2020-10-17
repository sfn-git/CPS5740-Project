<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <?php include("libraries.php")?>
</head>
<body>
    <div class="header">
        <div class="header-text">Search for an Item</div>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='customer_login.php'>Customer Login</a>
    </div>
    <input type="text" id="searchBar" onkeyup="search()" placeholder="Search for an item..." class="input-item search">
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='phase2.php'>Project Home</a>
    </div>
</body>
</html>