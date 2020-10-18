<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <?php include("libraries.php")?>
    <script src="./lib/jquery.min.js"></script>
    <script>
        function search(){

            console.log($('#searchBar').val());
            var search = $('#searchBar').val();
            
            $.ajax({

                url: 'search_result.php',
                method: 'GET',
                data: {"value": search},
                success: function(data){
                    console.log(data);
                }

            });

        };
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Salary');
        data.addColumn('boolean', 'Full Time Employee');
        data.addRows([
          ['Mike',  {v: 10000, f: '$10,000'}, true],
          ['Jim',   {v:8000,   f: '$8,000'},  false],
          ['Alice', {v: 12500, f: '$12,500'}, true],
          ['Bob',   {v: 7000,  f: '$7,000'},  true]
        ]);

        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '100%', margin: "auto"});
      }
    </script>

</head>
<body>
    <div class="header">
        <div class="header-text">Search for an Item</div>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='phase2.php'>Project Home</a>
    </div>
    <div class='user-link center-link' style='margin-top: 10px;'>
        <a href='customer_login.php'>Customer Login</a>
    </div>
    <input type="text" id="searchBar" onkeyup="search()" placeholder="Search for an item..." class="input-item search">
    <div id="table_div"></div>

</body>
</html>