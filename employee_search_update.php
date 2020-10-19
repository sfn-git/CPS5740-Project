<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Search and Update</title>
    <?php include("libraries.php")?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./lib/jquery.min.js"></script>
    <script>
        var searchResult;
        var vendors;

        function search(){
            var search = $('#searchBar').val();

            if(search != ""){
                $.ajax({

                    url: 'search_result.php',
                    method: 'POST',
                    data: {"value": search},
                    success: function(res){
                        if(!res){
                            $("#edit").hide();
                            $("#noResult").show();
                            drawTable();
                        }else{
                            $("#noResult").hide();
                            searchResult = JSON.parse(res);
                            console.log(searchResult);
                            drawTable(searchResult);
                            $('#edit').show();
                        }
                        
                    }
                    });
                };
            }
            

        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable(searchResult) {
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'Product ID');
            data.addColumn('string', 'Product Name');
            data.addColumn('string', 'Description');
            data.addColumn('number', 'Cost');
            data.addColumn('number', 'Sell Price');
            data.addColumn('number', 'Available Quantity');
            data.addColumn('string', 'Vendor Name');
            data.addColumn('string', 'Last Updated By');

            if(searchResult){
                for(var i in searchResult){
                    data.addRow([parseInt(searchResult[i].product_id), `${searchResult[i].name}`, `${searchResult[i].description}`, parseFloat(searchResult[i].cost), parseFloat(searchResult[i].sell_price), parseFloat(searchResult[i].quantity), `${searchResult[i].vName}`, `${searchResult[i].eName}`]);
                }
            }

            var table = new google.visualization.Table(document.getElementById('table_div'));

            table.draw(data, {showRowNumber: false, width: '100%', height: '100%'});
        }

        function editProducts(){
            $("#edit_table").show();
            $("#table_div").hide();
            $("#cancel").show();
            $("#save").show();
            $("#edit").hide();

            var insert = `
            <tr id="inner_edit_table" class="table_header">
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Sell Price</th>
                <th>Available Quantity</th>
                <th>Vendor Name</th>
                <th>Last Updated By</th>
            </tr>`;
            
            for(var i in searchResult){
                insert += `
                <tr>
                    <td>${searchResult[i].product_id}</td>
                    <td><input type="text" name="update[${i}][product_name]" id="update[${i}][product_name]" value="${searchResult[i].name}"></td>
                    <td><input type="text" name="update[${i}][description]" id="update[${i}][description]" value="${searchResult[i].description}"></td>
                    <td><input type="number" step=.01 min=0 name="update[${i}][cost]" id="update[${i}][cost]" value="${searchResult[i].cost}"></td>
                    <td><input type="number" step=.01 min=0 name="update[${i}][sell_price]" id="update[${i}][sell_price]" value="${searchResult[i].sell_price}"></td>
                    <td><input type="number" min=0 name="update[${i}][quantity]" id="update[${i}][quantity]" value="${searchResult[i].quantity}"></td>
                    <td>
                        <select name="update[${i}][vendor]" id="update[${i}][vendor]">
                            <?php 
                                include("dbconfig.php");
                                $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Unable to connect to database. Try again later");

                                $sql = "SELECT vendor_id, name FROM CPS5740.VENDOR";
                                $result = mysqli_query($conn, $sql);

                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value=\"".$row['vendor_id'] . "\">" .$row['name'] . "</option>";
                                }
                                mysqli_close($conn);
                            ?>
                        </select>
                    </td>
                    <td>${searchResult[i].eName}</td>
                </tr>`;
            };

            $("#edit_table").append(insert);

            for(var i in searchResult){
                document.getElementById(`update[${i}][vendor]`).value = searchResult[i].vendor_id;
            }
        }

        function cancelEdit(){
            $("#edit_table").hide();
            $("#table_div").show();
            $("#cancel").hide();
            $("#save").hide();
            $("#edit").show();
            $("#edit_table").html("");
        }

        function submitForm(){
            document.getElementById("updateForm").submit();
        }
    </script>
    
</head>
<body>
    <?php
    if(!isset($_COOKIE["employee_id"])){
            die("<div class='error-message'>You must be logged in as an employee or manager</div>");
        }
    ?>
    <div class='user-link center-link logout' style='margin-top: 10px;'>
        <a href='employee_logout.php'>Employee Logout</a>
    </div>
    <div class="header">
        <div class="header-text">Search for an item to view or update it</div>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='phase2.php'>Project Home</a>
    </div>
    

    <input type="text" id="searchBar" onkeyup="search()" placeholder="Search for an item..." class="input-item search" style="margin-bottom: 20px;">
    <div id="edit" onclick="editProducts()" class='user-link center-link edit' style='text-align: center; margin-bottom: 10px;'>
        <a href='#'>Edit Products</a>
    </div>
    <div id="cancel" onclick="cancelEdit()" class='user-link center-link' style='text-align: center; margin-bottom: 10px;'>
        <a href='#'>Cancel Edit</a>
    </div>
    <div id="table_div"></div>
        <form action="employee_update_product.php" id="updateForm" method="POST">
            <table id="edit_table" style="width: 95vw; display: none;"></table>
        </form>
    <div id="save" onclick="submitForm()" class='user-link center-link' style='text-align: center; margin-top: 10px;'>
        <a href='#'>Save Edits</a>
    </div>
</body>
</html>