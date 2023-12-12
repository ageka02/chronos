<?php require 'lib/connection.php'; 
$sql = "Select top 15 * from line ";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
  die( print_r( sqlsrv_errors(), true));
}

$sql1 = "Select top 15 * from line ";
$stmt1 = sqlsrv_query( $conn, $sql1);
if( $stmt1 === false ) {
  die( print_r( sqlsrv_errors(), true));
}

?>
<html>
    <head>
        <title>Belajarphp.net - ChartJS</title>
        
        <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
        <style type="text/css">
            .container {
                width: 50%;
                margin: 15px auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- <canvas id="myChartq" width="100" height="100"></canvas> -->
            <canvas id="myChart1" width="100"  height="100"></canvas>
        </div>
        

<script>
var ctx = document.getElementById('myChart1');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)'
            ],
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>


<!-- <script>
var ctx = document.getElementById("myChartq");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
       labels: [<?php //while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)) { echo '"' . $row[3] . '",';}?>],
        datasets: [{
                label: '# of Votes',
                data: [<?php //while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC)) { echo '"' . $row1[1] . '",';}?>],
                backgroundColor: 'rgba(0,103,255,.15)',
                borderColor: 'rgba(0,103,255,0.5)',
                borderWidth: 1
            }]
    },
    options: {
        scales: {
            yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
        }
    }
});
</script> -->
        
    </body>
</html>