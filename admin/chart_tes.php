<?php 
require '../lib/connection.php';
// require '../lib/Database.php';
// date_default_timezone_set('Asia/Jakarta');
// $db = new Database();
$building ='A1';
$dept = '121-AS1';
$date_scan = '2020-04-13';
$sql = "select l.[line code], lt.[line target], lt.[department code], convert(varchar,lt.[date input],23)[tgl]
            from [line]l
            left join [line target]lt on l.[line code] = lt.[Line code]
            where l.[line code] like '".$building."%' and lt.[department code] = '".$dept."' and convert(varchar,lt.[date input],23) = '".$date_scan."'
            order by l.[line code]";
            // echo $sql;
$stmt0 = sqlsrv_query( $conn, $sql);
// $sql_line = $db->select_line_scan($date_scan,$building,$dept);

// foreach ($sql_line as $data_line) { 
while( $row = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_NUMERIC) ) {
 ?>
	<div class="col">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">Output per hours <?php echo $row[0]; //$data_line['line_code']; ?> </h4>
                <canvas id="<?php echo 'oph'.$row[0];//$data_line['line_code']; ?>"></canvas>
            </div>
        </div>
    </div>

<?php 
}
// $sql_chart = $db->select_line_scan($date_scan,$building,$dept);
// foreach ($sql_chart as $data_chart) {
// 	$query_get_label = $db->get_oph($date_scan,$data_chart['line_code'],$data_chart['dept_code']);

// 	foreach ($query_get_label as $key => $value) {
// 		$jam[] = $key.",";
// 		$qty[] = $value;
// 	}
// print_r($jam);
// }
$stmt_chart = sqlsrv_query( $conn, $sql);
if( $stmt_chart === false ) {
     die( print_r( sqlsrv_errors(), true));
}

 while( $rwcht = sqlsrv_fetch_array( $stmt_chart, SQLSRV_FETCH_NUMERIC) ) {
$sqlchart = "select l.[line code],convert(varchar,bs.[date scan],23) [tanggal]
,SUBSTRING(convert(varchar, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam start]
,SUBSTRING(convert(varchar, dateadd(ss,3600+23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam end]   
,l.[line target], SUM(bs.[qty]) [QTY], bs.[department code] 
from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan] where convert(varchar,bs.[date scan],23) = '".$rwcht[3]."' AND bs.[scan type] = 'OUT' AND l.[line code] = '".$rwcht[0]."' and bs.[department code] = '".$rwcht[2]."'
GROUP BY l.[line code], convert(varchar,bs.[date scan],23)
,SUBSTRING(convert(varchar, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5)
,SUBSTRING(convert(varchar, dateadd(ss,3600+23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5)
,l.[line target], bs.[department code]";
$chartquery = sqlsrv_query( $conn, $sqlchart);
$chartquery1 = sqlsrv_query( $conn, $sqlchart);
$chartquery2 = sqlsrv_query( $conn, $sqlchart);

?>
<script>
var ctx = document.getElementById( "<?php echo 'oph'.$rwcht[0];//$data_line['line_code']; ?>" );
ctx.height = 70;
var myChart = new Chart( ctx, {    

    plugins: [ChartDataLabels],
    type: 'line',
    data: {
        labels: [ <?php while ($rowcq = sqlsrv_fetch_array( $chartquery, SQLSRV_FETCH_NUMERIC)) { echo '"' . $rowcq[2].'-' .$rowcq[3]. '",';}?> ],
        // type: 'bar',
        defaultFontFamily: 'Montserrat',
        datasets: [ {
                data: [ <?php while ($rowcq1 = sqlsrv_fetch_array( $chartquery1, SQLSRV_FETCH_NUMERIC)) { echo '"' . $rowcq1[5] . '",';}?> ],
                datalabels: {
                        align: 'end',
                        anchor: 'end',
                        display: true
                    },
                label: "QTY",
                backgroundColor: 'rgba(0,103,255,0)',
                borderColor: 'rgba(0,103,255,0.8)',
                borderWidth: 3.5,
                pointStyle: 'circle',
                pointRadius: 4,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'rgba(0,103,255,0.7)'
                }, 
                {
                data: [ <?php while ($rowcq2 = sqlsrv_fetch_array( $chartquery2, SQLSRV_FETCH_NUMERIC)) { echo '"' . $rowcq2[4] . '",';}?> ],
                datalabels: {
                            // align: 'start',
                            // anchor: 'start'
                            // display: true
                        },
                label: "TARGET",
                backgroundColor: 'rgba(200,10,90,0)',
                borderColor: 'rgba(200,10,90,0.8)',
                borderWidth: 3.5,
                pointStyle: 'circle',
                pointRadius: 3,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'rgba(200,10,90,0.7)'
        } ]
    },
    options: {        
        plugins: {
                    datalabels: {
                        backgroundColor: 'rgba(0,103,255,0.7)',
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: Math.round
                    }
                },
        responsive: true,
        tooltips: {
                    mode: 'index',
                    titleFontSize: 12,
                    cornerRadius: 3,
                    intersect: true,
                },
        legend: {
            display: true,
            position: 'top',
            labels: {
                usePointStyle: true,
                fontFamily: 'Montserrat'
            }
        },
        scales: {
            xAxes: [ {
                display: true,
                gridLines: {
                    display: true,
                    drawBorder: true
                }                
                    } ],
            yAxes: [ {
                display: true,
                gridLines: {
                    display: true,
                    drawBorder: false
                },
                scaleLabel: {
                    display: true,
                    labelString: 'QTY'
                },
                ticks: {
                    beginAtZero: true
                }
                    } ]
        },
        title: {
            display: false
        }
    }
} );
</script>
<?php } //end foreach awal ?>