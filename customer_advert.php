<?php

    $sql;
    if(!isset($_COOKIE['advert'])){
        $sql = "SELECT id, description, url FROM CPS5740.Advertisement WHERE category='OTHER'";
    }else{
        $term = $_COOKIE['advert'];
        $sql = "SELECT id, description, url FROM CPS5740.Advertisement WHERE category LIKE '%$term%' OR description LIKE '%$term%'";
    }

    include('dbconfig.php');
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");
    $result = mysqli_query($conn, $sql);

    $description;
    $id;
    $link;

    if(!($result && mysqli_num_rows($result)>0)){

        $sql = "SELECT id, description, url FROM CPS5740.Advertisement WHERE category='OTHER'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $description = $row['description'];
        $id = $row['id'];
        $link = $row['url'];

    }else{
        $row = mysqli_fetch_assoc($result);
        $description = $row['description'];
        $id = $row['id'];
        $link = $row['url'];
    }
    mysqli_close($conn);

    echo "<div class='advert'>";
            echo "<div class='image'>";
                echo "<img src='customer_get_ad_img.php?id=$id' width=250 height=250>";
            echo "</div>";
            echo "<div>";
                echo "$description";
            echo "</div>";
            
        echo "</div>";
?>