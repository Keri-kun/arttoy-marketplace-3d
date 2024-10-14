<html>

<head>

</head>

<body>
    
    <div id="piechart" style="width: 900px; height: 500px;"></div>

    <?php
    include 'include/script.php';
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(setup);

        function setup() {
            // chart 1
            $.ajax({
                type: "GET",
                url: "sql_chart.php",
                data: {
                    action: 'category'
                },
                dataType: "json",
                success: function(res) {
                    let array = []
                    array.push(['รายการ', 'จำนวน'])
                    res.forEach(e => {
                        array.push([e.CategoryName, e.num])
                    });
                    Chart1(array)
                }
            });

        }

        function Chart1(array) {

            var data = google.visualization.arrayToDataTable(array);

            var options = {
                title: 'จำนวนสินค้าแต่ละประเภท'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
</body>

</html>