<?php

class Database{

    private static $INSTANCE = null;
    private $sqlsrv,
            $HOST = '10.50.171.25',
            $USER = 'nyxuid',
            $PASS = 'slinyxpwd',
            $DBNAME = 'NYX_J2';

    public function __construct(){
        try{
            $this->sqlsrv = new PDO("sqlsrv:Server=$this->HOST;Database=$this->DBNAME", $this->USER, $this->PASS);
            $this->sqlsrv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Berhasil koneksi ke Database";
            
            }
        catch(PDOException $e) {
            die("Error connecting to SQL Server: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if(!isset(self::$INSTANCE)){
          self::$INSTANCE = new Database();
        }
          return self::$INSTANCE;
    }
    
    //FUNGSI UNTUK CEK ISI DATA
    public function cek_value($date, $building,$dept){
        // $gd = $building."%";
        $process = $dept."%";
        $query = "SELECT l.[line code], lt.[department code], lt.[line target]
                        FROM [line]l LEFT JOIN [line target]lt ON l.[line code] = lt.[line code]
                        WHERE l.[line code] = '".$building."'
                        AND lt.[department code] LIKE '".$process."'
                        AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."' GROUP BY l.[line code], lt.[department code], lt.[line target]
                        ORDER BY l.[line code]";
        $result = $this->sqlsrv->prepare($query);
        $result->execute();
        return $result->rowCount();
    }

    //GET LAST INPUT LINE TARGET
    public function last_target($line,$dept){

        $process = $dept."%";
        $query = "SELECT MAX(CONVERT(VARCHAR,[date input],23)) date
                    FROM [line target]
                    WHERE [line code] = '".$line."'
                    AND [department code] LIKE '".$process."'";
        $result = $this->sqlsrv->query($query);
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    //CEK DATA OPH APAKAH KOSONG ATAU TIDAK
    public function cek_data_oph($date,$line,$dept ){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam start]
                    , bs.[qty] [QTY] 
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND bs.[scan type] = 'OUT' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."')t
                    PIVOT( SUM(t.[qty])
                    FOR t.[jam start]
                    IN( [07:30],[08:30],[09:30],[10:30],[12:30],[13:30],[14:30],[15:30])
                    )AS pivot_table";
        $result = $this->sqlsrv->prepare($query);
        $result->execute();
        return $result->rowCount();
    }


    //FUNGSI UNTUK AMBIL DATA LINE 
    public function select_line($date,$building,$dept){
        // $gd = $building."%";
        $process = $dept."%";
        $query = "SELECT l.[line code], lt.[line target], lt.[department code]
                        FROM [line]l LEFT JOIN [line target]lt ON l.[line code] = lt.[line code]
                        WHERE l.[line code] = '".$building."'
                        AND lt.[department code] LIKE '".$process."'
                        AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."' 
                        AND lt.[department code] <> '121-CP1'
                        GROUP BY l.[line code], lt.[department code], lt.[line target]
                        ORDER BY l.[line code]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $line = $value[0];
            $dept_code = $value[2];
            $target = $value[1];

            $data[] = [
                "line_code" => $line,
                "dept_code" => $dept_code,
                "target" => $target
            ];
        }
        return $data;
    }

    //GET OUTPUT ACTUAL
    public function get_actual($date,$line,$dept){
        $query = "SELECT SUM(bs.[qty]) [qty] 
                    FROM [barcode scan]bs RIGHT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND bs.[scan type] = 'OUT' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."' 
                    AND CONVERT(VARCHAR,bs.[date scan],8) < '23:59'";
        $result = $this->sqlsrv->query($query);
        $actual = $result->fetch(PDO::FETCH_ASSOC);
        return $actual;
    }

    //GET JAM LAST SCAN DI HARI ITU
    public function get_last_scan($date,$line,$dept){
        $query = "SELECT SUBSTRING(MAX(CONVERT(VARCHAR,[date scan],8)),1,5) [last]
                    FROM [barcode scan] WHERE CONVERT(VARCHAR,[date scan],23) = '".$date."' 
                    AND [department code] = '".$dept."' 
                    AND [line code scan] = '".$line."' 
                    AND [scan type ] = 'OUT'";
        $result = $this->sqlsrv->query($query);
        $last = $result->fetch(PDO::FETCH_ASSOC);
        return $last;
    }

    //GET OPH HARI NORMAL (OUT)
    public function get_oph($date,$line,$dept){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam start]
                    , bs.[qty] [QTY], bs.[scan type]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."'
                    UNION ALL
                    SELECT '00:00',NULL,'IN'
                    UNION ALL
                    SELECT '00:00',NULL,'OUT')t
                    PIVOT( SUM(t.[qty])
                    FOR t.[jam start]
                    IN( [07:30],[08:30],[09:30],[10:30],[11:30],[12:30],[13:30],[14:30],[15:30]) 
                    )AS pivot_table";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                // "scan" => $value[0],
                "07:30" => $value[1],
                "08:30" => $value[2],
                "09:30" => $value[3],
                "10:30" => $value[4],
                "11:30" => $value[5],
                "12:30" => $value[6],
                "13:30" => $value[7],
                "14:30" => $value[8],
                "15:30" => $value[9]
            ];
        }
        
        return $data;
    }

    //GET OPH OVERTIEM HARI NORMAL
    public function get_overtime($date,$line,$dept){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam start]
                    , bs.[qty] [QTY], bs.[scan type]
                    FROM [barcode scan]bs left join [line]l on l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."'
                    UNION ALL
                    SELECT '00:00',NULL,'IN'
                    UNION ALL
                    SELECT '00:00',NULL,'OUT')t
                    PIVOT( sum(t.[qty])
                    FOR t.[jam start]
                    IN( [16:30],[17:30],[18:30],[19:30],[20:30])
                    )AS pivot_table";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                // "scan" => $value[0],
                "16:30" => $value[1],
                "17:30" => $value[2],
                "18:30" => $value[3],
                "19:30" => $value[4],
                "20:30" => $value[5],
            ];
        }
        return $data;
    }

    //GET OPH HARI JUM'AT PAGI
    public function get_jumat_pagi($date,$line,$dept){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(ss,23400+(3600*(datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1)),datediff(dd,0,[date scan])), 8),1,5) [jam start]
                    , bs.[qty] [QTY], bs.[scan type]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."'
                    UNION ALL
                    SELECT '00:00',NULL,'IN'
                    UNION ALL
                    SELECT '00:00',NULL,'OUT')t
                    PIVOT( sum(t.[qty])
                    FOR t.[jam start]
                    IN( [07:30],[08:30],[09:30],[10:30],[11:30])
                    )AS pivot_table";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                // "scan" => $value[0],
                "07:30" => $value[1],
                "08:30" => $value[2],
                "09:30" => $value[3],
                "10:30" => $value[4],
                "11:30" => $value[5],
            ];
        }
        return $data;
    }

    //GET OPH JUM'AT SIANG
    public function get_jumat_siang($date,$line,$dept){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(hh,datediff(hh,0,bs.[date scan]),00), 8),1,5)  [jam start]
                    ,bs.[qty] [QTY],bs.[scan type]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."'
                    UNION ALL
                    SELECT '00:00',NULL,'IN'
                    UNION ALL
                    SELECT '00:00',NULL,'OUT')t
                    PIVOT( SUM(t.[qty])
                    FOR t.[jam start]
                    IN( [13:00],[14:00],[15:00],[16:00])
                    )AS pivot_table";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                // "scan" => $value[0],
                "13:00" => $value[1],
                "14:00" => $value[2],
                "15:00" => $value[3],
                "16:00" => $value[4],
            ];
        }
        return $data;
    }

    //GET OVERTIEM DI HARI JUM'AT
    public function get_overtime_jumat($date,$line,$dept){
        $query = "SELECT * FROM(
                    SELECT SUBSTRING(CONVERT(VARCHAR, dateadd(hh,datediff(hh,0,bs.[date scan]),00), 8),1,5)  [jam start]
                    ,bs.[qty] [QTY], bs.[scan type]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan] 
                    WHERE CONVERT(VARCHAR,bs.[date scan],23) = '".$date."' 
                    AND l.[line code] = '".$line."' 
                    AND bs.[department code] = '".$dept."'
                    UNION ALL
                    SELECT '00:00',NULL,'IN'
                    UNION ALL
                    SELECT '00:00',NULL,'OUT')t
                    PIVOT( sum(t.[qty])
                    FOR t.[jam start]
                    IN( [17:00],[18:00],[19:00],[20:00],[21:00])
                    )AS pivot_table";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                // "scan" => $value[0],
                "17:00" => $value[1],
                "18:00" => $value[2],
                "19:00" => $value[3],
                "20:00" => $value[4],
                "21:00" => $value[5],
            ];
        }
        return $data;
    }

    //CEK VALUE PPH
    public function cek_pph($date,$building,$dept){
        $gd = $building."%";
        $process = $dept."%";
        $query = "SELECT a.[line code], a.[department code], lt.[line target], a.[mp standard], a.[mp actual], a.[pph standard] from pph a
                inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
                where bs.[line code scan] like '".$gd."'
                and bs.[scan type] = 'OUT'
                and bs.[department code] LIKE '".$process."'
                and CONVERT(VARCHAR,bs.[date scan],23) = '".$date."'
                group by a.[line code], a.[department code], lt.[line target],a.[mp standard], a.[mp actual], a.[pph standard]";
        $result = $this->sqlsrv->prepare($query);
        $result->execute();
        return $result->rowCount();

    }

    //GET PPH
    public function get_pph_standard($date,$building,$dept){
        $gd = $building."%";
        $process = $dept."%";
        $query = "SELECT a.[line code], a.[department code], lt.[line target], a.[mp standard], a.[mp actual], a.[pph standard],
                a.[mp overtime], a.[hours overtime]
                from pph a
                inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
                where bs.[line code scan] like '".$gd."'
                and bs.[scan type] = 'OUT'
                and bs.[department code] LIKE '".$process."'
                and CONVERT(VARCHAR,bs.[date scan],23) = '".$date."'
                group by a.[line code], a.[department code], lt.[line target],a.[mp standard], a.[mp actual], a.[pph standard],a.[mp overtime], a.[hours overtime]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "dept_code" => $value[1],
                "target" => $value[2],
                "mp_standard" => round($value[3],1),
                "mp_actual" => $value[4],
                "pph_standard" => round($value[5],2),
                "mp_ot" => round($value[6]),
                "h_ot" => round($value[7])
            ];
        }
        return $data;
    }

    //GET TABLE BALANCE
    public function get_balance_table($date,$line,$dept){
        $query = "SELECT jo.[po no], jo.[po item],jo.[bucket],jo.[ogac], SUBSTRING(jo.[output product code],1,17),jo.[output product code],jo.[output product name],
                    SUBSTRING(jo.[output product code],19,3)[size], jo.[qty], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out] FROM [job order]jo
                    LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                    WHERE convert(varchar,bs.[date scan],23) = '".$date."'
                    AND bs.[line code scan] = '".$line."'
                    AND bs.[department code] = '".$dept."'                    
                    GROUP BY jo.[po no], jo.[po item],jo.[bucket],jo.[ogac],jo.[output product code], jo.[qty],jo.[output product name]
                    ORDER BY jo.[qty] DESC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            // if ($value[8] != '0'){

                $query2 = "SELECT SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out] FROM [job order]jo
                    LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                    WHERE convert(varchar,bs.[date scan],23) <= '".$date."'
                    AND bs.[line code scan] = '".$line."'
                    AND bs.[department code] = '".$dept."'
                    AND jo.[po no]+jo.[po item]+jo.[output product code] = '".$value[0].$value[1].$value[5]."'";
                $result2 = $this->sqlsrv->query($query2);
                while ($value2 = $result2->fetch(PDO::FETCH_NUM)) {
                    $data[] = [
                            "po_no" => $value[0],
                            "po_item" => $value[1],
                            "bucket" => $value[2],
                            "ogac" => $value[3],
                            "style" => $value[4],
                            "desc" => $value[6],
                            "size" => $value[7],
                            "qty_order" => $value[8],
                            "qty_in" => $value[9],
                            "qty_out" => $value[10],
                            "total_in" => $value2[0],
                            "total_out" => $value2[1]
                ];

                }
            // }
            
        }
        return $data;
    }

    //GET PERFORMANCE OPH A WEEK
    public function perf_week($date,$line,$dept){
        $query = "SELECT * FROM ( SELECT TOP 5 CONVERT(VARCHAR,bs.[date scan],23)[dateku],SUM(bs.[qty])[qty], lt.[line target], lt.[department code],l.[line code]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan]
                    JOIN [line target]lt ON lt.[line code] = l.[line code] and CONVERT(VARCHAR,lt.[date input],23) = CONVERT(VARCHAR,bs.[date scan],23) and bs.[department code] = lt.[department code]
                    WHERE l.[line code] like '".$line."'
                    AND bs.[department code] = '".$dept."'
                    AND CONVERT(VARCHAR,bs.[date scan],23) <= '".$date."'
                    AND bs.[scan type] = 'OUT'
                    GROUP BY convert(varchar,bs.[date scan],23), lt.[line target], lt.[department code],l.[line code]
                    ORDER BY convert(varchar,bs.[date scan],23) DESC
                    )tr     
                    order by convert(varchar,[dateku],23) ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "tgl" => $value[0],
                "oph" => round($value[1]),
                "target" => $value[2]*8,
                "dept_code" => $value[3],
                "line_code" => $value[4]
            ];
        }
        return $data;
    }

    //GET PPH A WEEK
    public function get_pph_week($date,$line,$dept){
        $query = "SELECT * FROM (SELECT TOP 5 convert(varchar,a.[date],23) [date],a.[pph standard],SUM(bs.[qty])[actual], a.[mp actual],a.[mp overtime],a.[hours overtime] from pph a
                inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
                where bs.[line code scan] like '".$line."'
                and bs.[scan type] = 'OUT'
                and bs.[department code] = '".$dept."'
                AND CONVERT(VARCHAR,bs.[date scan],23) <= '".$date."'
                group by a.[pph standard],a.[mp actual],a.[mp overtime],a.[hours overtime],convert(varchar,a.[date],23)
                ORDER BY convert(varchar,a.[date],23) DESC) tb ORDER BY convert(varchar,[date],23) ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "tgl" => $value[0],
                "pph_standard" => round($value[1],2),
                "output_actual" => round($value[2]),
                "mp_actual" => round($value[3]*8),
                "mp_ot" => round($value[4]*$value[5])
            ];
        }
        return $data;
    }

    public function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }


    //GET SUMMARY OUT IN A DAY
    public function get_summary($date){

        $query = "SELECT * FROM (SELECT l.[line id],bs.[line code scan] line,bs.[department code] dept_code, lt.[line target],bs.[qty] qty
                        FROM [barcode scan]bs LEFT JOIN [line target]lt ON bs.[department code] = lt.[department code]
                        AND bs.[line code scan] = lt.[line code]
                        AND convert(varchar,bs.[date scan],23) = convert(varchar,lt.[date input],23)
                        LEFT JOIN [line]l ON l.[line code] = bs.[line code scan]
                        WHERE bs.[scan type] = 'OUT'
                        AND convert(varchar,bs.[date scan],23) = '".$date."'
                        AND bs.[line code scan] LIKE 'Line%')tb
                        PIVOT (sum(tb.[qty])
                        FOR tb.dept_code IN ([121-AS1], [121-ST1])
                        )as PVT_TABLE
                        ORDER BY [line id]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line" => $value[1],
                "target" => $value[2]*8,
                "qty_asy" => round($value[3]),
                "qty_stc" => round($value[4]),
                "percentage" => round(($value[3]/($value[2]*8)*100),2)
            ];
        }
        return $data;
        
    }

    public function fg_data($date){

        $query = "Select sum(tb.qty_in)total_in,sum(tb.qty_out)total_out,sum(tb2.qty_in)current_in,sum(tb3.qty_out)last_out 
                     from(
                    select mul.[ucc barcode no],mulsi.[quantity]qty_in, mulso.[quantity]qty_out
                    from [Mercury UCC Label]mul
                    inner join [mercury UCC label scan in]mulsi on mul.[ucc barcode no] = mulsi.[ucc barcode no]
                    left join [mercury UCC label scan out]mulso on mul.[ucc barcode no] = mulso.[ucc barcode no]
                    where 1=1
                    and convert(varchar, mulsi.[date input],23) <= '".$date."'
                    )tb
                    left join (
                    select mulsi.[ucc barcode no], mulsi.[quantity]qty_in
                    from [mercury UCC label scan in]mulsi 
                    where 1=1
                    and convert(varchar, mulsi.[date input],23) = '".$date."'
                     )tb2
                    on tb.[ucc barcode no]=tb2.[ucc barcode no]
                    left join (
                    select mulso.[ucc barcode no], mulso.[quantity]qty_out
                    from [mercury UCC label scan out]mulso 
                    where convert(varchar, dateadd(dd,1,mulso.[date input]),23) = '".$date."'
                     )tb3
                    on tb.[ucc barcode no]=tb3.[ucc barcode no]
                    ";
        $result = $this->sqlsrv->query($query);
        $data = array();
        $curr_in = $total_in = $total_out = $last_out = 0;
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            if ($value[2] != null) {
                $curr_in = round($value[2]);
            }
            if ($value[0] != null) {
                $total_in = round($value[0]);
            }
            if ($value[3] != null) {
                $last_out = round($value[3]);
            }
            if ($value[1] != null) {
                $total_out = round($value[1]);
            }


            $data[] = [
                "current_in" => $curr_in,
                "stok" => $total_in-$total_out,
                "last_out" => $last_out
            ];
        }

        return $data;
    }

    
}

?>