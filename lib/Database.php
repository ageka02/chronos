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
    public function cek_value($date, $building){
        $gd = $building."%";
        $query = "SELECT l.[line code], lt.[department code], lt.[line target]
                        FROM [line]l LEFT JOIN [line target]lt ON l.[line code] = lt.[line code]
                        WHERE l.[line code] LIKE '".$gd."'
                        AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."' GROUP BY l.[line code], lt.[department code], lt.[line target]
                        ORDER BY l.[line code]";
        $result = $this->sqlsrv->prepare($query);
        $result->execute();
        return $result->rowCount();
    }

    public function last_target($date,$building,$dept){
        // $process = $dept."%";
        // $gd=$building."%";
        $query = "SELECT MAX(CONVERT(VARCHAR,[date input],23)) date
                    FROM [line target]
                    WHERE [line code] = '".$building."'
                    AND [department code] like '".$dept."'
                    AND CONVERT(VARCHAR,[date input],23) <= '".$date."'";
        $result = $this->sqlsrv->query($query);
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function cek_value_onhand($building,$dept){
        $gd = $building."%";
        $query = "SELECT l.[line code], bs.[department code]
                        FROM [barcode scan]bs RIGHT JOIN [line]l ON l.[line code] = bs.[line code scan]
                        WHERE l.[line code] LIKE '".$gd."'
                        and bs.[department code] = '".$dept."'
                        GROUP BY l.[line code], bs.[department code]
                        ORDER BY l.[line code]";
        $result = $this->sqlsrv->prepare($query);
        $result->execute();
        return $result->rowCount();
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
    public function select_line($date,$building){
        $gd = $building."%";
        $query = "SELECT l.[line code], lt.[line target], lt.[department code]
                        FROM [line]l LEFT JOIN [line target]lt ON l.[line code] = lt.[line code]
                        WHERE l.[line code] LIKE '".$gd."'
                        AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."' GROUP BY l.[line code], lt.[department code], lt.[line target]
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

    public function select_line_home($building,$dept){
        $gd = $building."%";
        $query = "SELECT l.[line code], lt.[department code]
                        FROM [barcode scan]bs
                        join [line target]lt on lt.[line code] = bs.[line code scan]
                        join [line]l on l.[line code] = lt.[line code]
                        WHERE l.[line code] LIKE '".$gd."'
                        AND lt.[department code] like '".$dept."'
                        GROUP BY l.[line code],l.[line ID], lt.[department code]
                        ORDER BY l.[line ID], lt.[department code]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $line = $value[0];
            $dept_code = $value[1];

            $data[] = [
                "line_code" => $line,
                "dept_code" => $dept_code
            ];
        }
        return $data;
    }

    public function select_line_onhand2($date,$building,$dept){
        $gd = $building."%";
        $query = "SELECT l.[line code], lt.[line target], lt.[department code]
                        FROM [line]l LEFT JOIN [line target]lt ON l.[line code] = lt.[line code]
                        WHERE l.[line code] LIKE '".$gd."'
                        AND lt.[department code] = '".$dept."'
                        AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."' GROUP BY l.[line code], lt.[department code], lt.[line target]
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
                    IN( [16:30],[17:30],[18:30],[19:30],[20:30] )
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
                "20:30" => $value[5]
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
                "11:30" => $value[5]
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
    public function canvas_pph($date,$building,$dept){
        $gd = $building."%";
        // $process = $dept."%";
        $query = "SELECT distinct substring(a.[line code],1,1)[line code], a.[department code], CONVERT(VARCHAR,a.[date ],23)  
                    from pph a
                    inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                    where bs.[line code scan] like '".$gd."'
                    and bs.[department code] LIKE '".$dept."'
                    and CONVERT(VARCHAR,bs.[date scan],23) = '".$date."'
                    ";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "dept_code" => $value[1],
                "date" => $value[2]
            ];
        }
        return $data;
    }

    //GET PPH
    public function get_pph($date,$building,$dept){
        $gd = $building."%";
        $query = "SELECT a.[line code], a.[department code], a.[mp standard], a.[mp actual], a.[pph standard], a.[mp overtime], a.[hours overtime], a.[date], lt.[line target],SUM(bs.[qty])[actual], l.[Line ID] from pph a
                inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
                inner join [line]l on l.[line code] = lt.[line code]
                where bs.[line code scan] like '".$gd."'
                and bs.[scan type] = 'OUT'
                and bs.[department code] LIKE '".$dept."'
                and CONVERT(VARCHAR,bs.[date scan],23) = '".$date."'
                group by a.[line code], l.[line ID], a.[department code], a.[mp standard], a.[mp actual], a.[pph standard], a.[mp overtime], a.[hours overtime], a.[date], lt.[line target]
                ORDER BY l.[Line ID]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "dept_code" => $value[1],
                "mp_standard" => $value[2],
                "mp_actual" => $value[3],
                "pph_standard" => $value[4],
                "mp_overtime" => $value[5],
                "hours_overtime" => $value[6],
                "date" => $value[7],
                "target" => $value[8],
                "actual" => $value[9],
                "line_id" => $value[10],
                "mp_balance" => $value[3] - $value[2],
                "mh_normal" => $value[3]*8,
                "overtime" => $value[5] * $value[6]
            ];
        }
        return $data;
    }

    public function get_pph_week($date,$building,$dept){
        $gd = $building."%";
        // $query = "SELECT a.*, lt.[line target],SUM(bs.[qty])[actual] from pph a
        //         inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
        //         inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
        //         where bs.[line code scan] like '".$gd."'
        //         and bs.[scan type] = 'OUT'
        //         and bs.[department code] = '".$dept."'
        //         AND DATEADD(DD,DATEDIFF(DD,0,bs.[date scan]),0) BETWEEN DATEADD(dd,-5,'".$date."') AND CONVERT(DATETIME, '".$date."', 111)
        //         group by a.[line code], a.[department code], a.[date],a.[mp standard], a.[mp actual], a.[pph standard], a.[mp overtime], a.[hours overtime], lt.[line target]
        //         ORDER BY convert(varchar,a.[date],23) ASC";
                
        $query = "SELECT * FROM ( SELECT TOP 5 a.*, lt.[line target],SUM(bs.[qty])[actual] from pph a
                inner join [barcode scan]bs on bs.[line code scan] = a.[line code] and convert(varchar,a.[date],23) = convert(varchar,bs.[date scan],23) and a.[department code] = bs.[department code]
                inner join [line target]lt on a.[line code] = lt.[line code] and convert(varchar,a.[date],23) = convert(varchar,lt.[date input],23) and a.[department code] = lt.[department code]
                where bs.[line code scan] like '".$gd."'
                and bs.[scan type] = 'OUT'
                and bs.[department code] = '".$dept."'
                AND CONVERT(VARCHAR,bs.[date scan],23) <= '".$date."'
                group by a.[line code], a.[department code], a.[date],a.[mp standard], a.[mp actual], a.[pph standard], a.[mp overtime], a.[hours overtime],a.[overtime filled], lt.[line target]
                ORDER BY convert(varchar,a.[date],23) DESC      
                )tr
                order by convert(varchar,[date],23) ASC ";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "dept_code" => $value[1],
                "mp_standard" => $value[2],
                "mp_actual" => $value[3],
                "pph_standard" => $value[4],
                "mp_overtime" => $value[5],
                "hours_overtime" => $value[6],
                "date" => substr($value[7], 0,10),
                "target" => $value[8],
                "actual" => $value[9],
                "mp_balance" => $value[3] - $value[2],
                "mh_normal" => $value[3]*8,
                "overtime" => $value[5] * $value[6]
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
        $query = "SELECT CONVERT(VARCHAR,bs.[date scan],23),SUM(bs.[qty])
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan]
                    WHERE l.[line code] = '".$line."'
                    AND bs.[department code] = '".$dept."'
                    AND DATEADD(DD,DATEDIFF(DD,0,bs.[date scan]),0) BETWEEN DATEADD(dd,-5,'".$date."') AND CONVERT(DATETIME, '".$date."', 111)
                    AND bs.[scan type] = 'OUT'
                    GROUP BY convert(varchar,bs.[date scan],23)
                    ORDER BY convert(varchar,bs.[date scan],23) ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "tgl" => $value[0],
                "oph" => $value[1]
            ];
        }
        return $data;
    }

    public function perf_week_adm($date,$line,$dept){
        $query = "SELECT * FROM ( SELECT TOP 5 CONVERT(VARCHAR,bs.[date scan],23)[dateku],SUM(bs.[qty])[qty], bs.[department code],l.[line code]
                    FROM [barcode scan]bs LEFT JOIN [line]l ON l.[line code] = bs.[line code scan]
                    WHERE l.[line code] like '".$line."'
                    AND bs.[department code] = '".$dept."'
                    AND CONVERT(VARCHAR,bs.[date scan],23) <= '".$date."'
                    AND bs.[scan type] = 'OUT'
                    GROUP BY convert(varchar,bs.[date scan],23), bs.[department code],l.[line code]
                    ORDER BY convert(varchar,bs.[date scan],23) DESC
                    )tr 
                    order by convert(varchar,[dateku],23) ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "tgl" => $value[0],
                "oph" => $value[1],
                // "target" => $value[2]*8,
                "dept_code" => $value[2],
                "line_code" => $value[3]
            ];
        }
        return $data;
    }

    public function perf_week_target($date,$line,$dept){
        $query = "SELECT * FROM ( SELECT TOP 5 CONVERT(VARCHAR,lt.[date input],23)[dateku], lt.[department code],[line target], lt.[line code]
                    FROM [line target]lt
                    WHERE lt.[line code] like '".$line."'
                    AND lt.[department code] = '".$dept."'
                    AND CONVERT(VARCHAR,lt.[date input],23) <= '".$date."'
                    ORDER BY convert(varchar,lt.[date input],23) DESC
                    )tr 
                    order by convert(varchar,[dateku],23) ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "date" => $value[0],
                "dept_code" => $value[1],
                "target" => $value[2]*8,
                "line_code" => $value[3]
            ];
        }
        return $data;
    }


    public function get_onhand_table($date,$line,$dept,$size){
        $query = "SELECT jo.[po no], jo.[po item],jo.[bucket],jo.[ogac], SUBSTRING(jo.[output product code],1,17),jo.[output product code],jo.[output product name],
                    SUBSTRING(jo.[output product code],19,3)[size], jo.[qty], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out] FROM [job order]jo
                    LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                    WHERE convert(varchar,bs.[date scan],23) = '".$date."'
                    AND bs.[line code scan] = '".$line."'
                    AND bs.[department code] = '".$dept."'
                    AND SUBSTRING(jo.[output product code],19,3) = '".$size."'
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

    public function get_data_wip($date,$line,$dept){
        $query = " SELECT [size], SUM([qty in]-[qty out])[wip] from(
SELECT jo.[po no],jo.[po item],jo.[output product code], SUBSTRING(jo.[output product code],19,3)[size], jo.[qty], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out] FROM [job order]jo
                LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                WHERE convert(varchar,bs.[date scan],23) = '".$date."'
                AND bs.[line code scan] = '".$line."'
                AND bs.[department code] = '".$dept."'       
            GROUP BY jo.[po no],jo.[po item], jo.[output product code],jo.[output product name],SUBSTRING(jo.[output product code],19,3),jo.[qty]
                    )t group by [size]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {           
                    $data[] = [
                            "size" => $value[0],
                            "qty" => $value[1]
                    ];    
        }
        return $data;
    }

     public function tes_onhand_bener($date,$dept){
        $dept2 = '';
        if ($dept == '121-CP1') {
            $dept2 = '121-PRE';
        }elseif ($dept == '121-PRE') {
            $dept2 = '121-ST1';
        }elseif ($dept == '121-ST1') {
            $dept2 = '121-AS1';
        }elseif ($dept == '121-AS1') {
            $dept2 = '121-FGD';
        }elseif ($dept == '121-FGD') {
            $dept2 = '';
        }elseif ($dept == '121-SC0') {
            $dept2 = '121-SC1';
        }elseif ($dept == '121-SC1') {
            $dept2 = '121-PRE';
        }elseif ($dept == '121-PT1') {
            $dept2 = '121-DS1';
        }elseif ($dept == '121-DS1') {
            $dept2 = '121-AS1';
        }
        

        $query = "SELECT tbl1.[po no], tbl1.[po item], tbl1.[style], tbl1.[output product name],[qty in1],[qty out1],[qty in2],[qty out2], [qty out1]-[qty in2] [onhandku]
                FROM
                (SELECT jo.[po no], jo.[po item],jo.[bucket],jo.[ogac], SUBSTRING(jo.[output product code],8,14)[style],jo.[output product code],jo.[output product name],SUBSTRING(jo.[output product code],19,3)[size], jo.[qty], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in1], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out1] FROM [job order]jo
                    LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                    WHERE convert(varchar,bs.[date scan],23) <= '".$date."'
                    AND bs.[department code] = '".$dept."'
                    GROUP BY jo.[po no], jo.[po item],jo.[bucket],jo.[ogac],jo.[output product code], jo.[qty],jo.[output product name]
                    ) tbl1
                left join
                (SELECT jo.[po no], jo.[po item],jo.[bucket],jo.[ogac], SUBSTRING(jo.[output product code],8,14)[style],jo.[output product code],jo.[output product name],
                SUBSTRING(jo.[output product code],19,3)[size], jo.[qty], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in2], SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out2] FROM [job order]jo
                LEFT JOIN [barcode scan]bs ON jo.[jo id] = bs.[jo id]
                WHERE convert(varchar,bs.[date scan],23) <= '".$date."'
                AND bs.[department code] = '".$dept2."'
                GROUP BY jo.[po no], jo.[po item],jo.[bucket],jo.[ogac],jo.[output product code], jo.[qty],jo.[output product name]
                )tbl2
                on tbl1.[po no] = tbl2.[po no] and tbl1.[po item] = tbl2.[po item] and tbl1.[style]=tbl2.[style]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                    "po_no" => $value[0],
                    "po_item" => $value[1],
                    "style" => $value[2],
                    "desc" => $value[3],
                    "qty_in1" => $value[4],
                    "qty_out1" => $value[5],
                    "qty_in2" => $value[6],
                    "qty_out2" => $value[7],
                    "onhand" => $value[5]-$value[6]
            ];
                     
        }
        return $data;
    }

// ADMIN CLASS =========================================================================================

    // GET Gedung
    // public function get_building(){  
    //     $query = "SELECT distinct SUBSTRING([line code],1,1) from LINE where [line code] not like '%NCVS%'";
    //     $result = $this->sqlsrv->query($query);
    //     $data = array();
    //     while ($value = $result->fetch(PDO::FETCH_NUM)) {
    //         $data[] = ["gedung" => $value[0]];
    //     }
    //     return $data;
    // }
    public function get_building(){  
        $query = "SELECT [line code] from LINE where [line code] like '%Line%' 
                    ORDER BY [line ID]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = ["gedung" => $value[0]];
        }
        return $data;
    }
    public function get_line(){  
        $query = "SELECT distinct [line code] from LINE where [line code] not like '%NCVS%'";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = ["line" => $value[0]];
        }
        return $data;
    }

    //FUNGSI UNTUK AMBIL DATA LINE SCAN
    public function select_line_scan($date,$line,$dept){
        // $gd = $line."%";
        $query = "SELECT l.[line code], lt.[line target], lt.[department code]
            FROM [line]l
            LEFT JOIN [line target]lt  ON l.[line code] = lt.[Line code]
            WHERE l.[line code] LIKE '".$line."' 
            AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."'
            AND lt.[department code] LIKE '".$dept."' 
            ORDER BY l.[line ID] ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "target" => $value[1],
                "dept_code" => $value[2]
            ];
        }
        return $data;
    }

    public function select_line_new($date,$lineID,$dept,$topku){
        $query = "SELECT ".$topku." l.[line code], lt.[line target], lt.[department code]
            FROM [line]l
            LEFT JOIN [line target]lt  ON l.[line code] = lt.[Line code]
            WHERE l.[line ID] >= '".$lineID."' 
            AND CONVERT(VARCHAR,lt.[date input],23) = '".$date."'
            AND lt.[department code] LIKE '".$dept."' 
            ORDER BY l.[line ID] ASC";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "line_code" => $value[0],
                "target" => $value[1],
                "dept_code" => $value[2]
            ];
        }
        return $data;
    }

    // query onhand
    public function select_data_onhand($date,$line,$dept){
        $gd = $line."%";
        $query = "SELECT [size], [qty in] - [qty out] [onhand] FROM(
                    SELECT bs.[size]
                    ,SUM(CASE WHEN bs.[scan type] = 'IN' THEN bs.[qty] else 0 end) [qty in]
                    ,SUM(CASE WHEN bs.[scan type] = 'OUT' THEN bs.[qty] else 0 end) [qty out]
                     FROM [barcode scan] bs
                     LEFT JOIN [line] l
                     ON l.[line code] = bs.[line code scan]
                      WHERE 1=1
                     AND bs.[line code scan] LIKE '".$line."' and convert(varchar,bs.[date scan],23) BETWEEN '2020-01-01' and  '".$date."' and bs.[department code] = '".$dept."'
                     group by bs.[size] )t";
         $result = $this->sqlsrv->query($query);
         $data = array();
         while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $size = $value[0];
            $onhand = $value[1];

            $data[] = [
                "size" => $size,
                "onhand" => $onhand
            ];
        }
        return $data;
    }

    //query finish good
    public function select_fg($date){
        $query = "SELECT tb.[po no], tb.[po item], tb.[fg name], tb.[fg code], tb.[item segment 4], sum(tb.qty_in)total_qty_in,sum(tb.qty_out)total_qty_out,sum(tb2.qty_in)qty_in_current,sum(tb2.qty_out)qty_out_current,count(tb.[carton qty])carton_qty 
             from(
            select mul.[ucc barcode no],mul.[PO No],mul.[po item],mul.[fg name],mul.[fg code], mul.[item segment 4],mulsi.[quantity]qty_in, mulso.[quantity]qty_out,mul.[carton qty]
            from [Mercury UCC Label]mul
            inner join [mercury UCC label scan in]mulsi on mul.[ucc barcode no] = mulsi.[ucc barcode no]
            left join [mercury UCC label scan out]mulso on mul.[ucc barcode no] = mulso.[ucc barcode no]
            where 1=1
            and convert(varchar, mulsi.[date input],23) <= '".$date."'
            )tb
            left join (
            select mulsi.[ucc barcode no],mulsi.[po no], mulsi.[po item], mulsi.[quantity]qty_in , mulso.[quantity]qty_out
            from [mercury UCC label scan in]mulsi 
            left join [mercury UCC label scan out]mulso on mulsi.[ucc barcode no] = mulso.[ucc barcode no]
            where 1=1
            and convert(varchar, mulsi.[date input],23) = '".$date."'
             )tb2
            on tb.[ucc barcode no]=tb2.[ucc barcode no] --and tb.[po item] = tb2.[po item]
            group by tb.[po no], tb.[po item], tb.[fg name], tb.[fg code], tb.[item segment 4]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "po_no" => $value[0],
                "po_item" => $value[1],
                "fg_name" => $value[2],
                "fg_code" => $value[3],
                "size" => $value[4],
                "total_qty_in" => $value[5],
                "total_qty_out" => $value[6],
                "qty_in_current" => $value[7],
                "qty_out_current" => $value[8],
                "carton_qty" => $value[9],
                "stock" => $value[5] - $value[6]
            ];
        }
        return $data;
    }
    public function select_fg_summary($date){
        $query = "SELECT tb.[po no],tb.[po item],tb.[fg name],tb.[fg code], sum(tb.qty_in)total_qty_in,sum(tb.qty_out)total_qty_out,sum(tb2.qty_in)qty_in_current,sum(tb2.qty_out)qty_out_current 
             from(
            select mul.[ucc barcode no],mul.[PO No],mul.[po item],mul.[fg name],mul.[fg code],mulsi.[quantity]qty_in, mulso.[quantity]qty_out
            from [Mercury UCC Label]mul
            inner join [mercury UCC label scan in]mulsi on mul.[ucc barcode no] = mulsi.[ucc barcode no]
            left join [mercury UCC label scan out]mulso on mul.[ucc barcode no] = mulso.[ucc barcode no]
            where 1=1
            and convert(varchar, mulsi.[date input],23) <= '".$date."'
            )tb
            left join (
            select mulsi.[ucc barcode no],mulsi.[po no], mulsi.[po item], mulsi.[quantity]qty_in, mulso.[quantity]qty_out
            from [mercury UCC label scan in]mulsi 
            left join [mercury UCC label scan out]mulso on mulsi.[ucc barcode no] = mulso.[ucc barcode no]
            where 1=1
            and convert(varchar, mulsi.[date input],23) = '".$date."'
             )tb2
            on tb.[ucc barcode no]=tb2.[ucc barcode no]
            group by tb.[po no], tb.[po item], tb.[fg name], tb.[fg code]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            $data[] = [
                "po_no" => $value[0],
                "po_item" => $value[1],
                "fg_name" => $value[2],
                "fg_code" => $value[3],
                "total_qty_in" => $value[4],
                "total_qty_out" => $value[5],
                "qty_in_current" => $value[6],
                "qty_out_current" => $value[7],
                "stock" => $value[4] - $value[5]
            ];
        }
        return $data;
    }

    public function report_all_process_tot($date,$bucket_from,$bucket_to){
        $query= "";
        
    }
    public function report_all_process($date,$bucket_from,$bucket_to){
        $query = "SELECT [Line Code],  
       [Bucket], 
       [Po No], 
       [Po Item], 
       tbl_ok.[Style], 
       [Description], 
       [Gender], 
       convert(varchar, min([Ogac]),23) [Ogac], 
       max([Line_Qty]) [Line_Qty],
       sum([st1 qty in day]) [st1 in],
       sum([st1 qty out day]) [st1 out],
       sum([as1 qty in day]) [as1 in],
       sum([as1 qty out day]) [as1 out],
       sum([cp1 qty in day]) [cp1 in],
        sum([cp1 qty out day]) [cp1 out],
        sum([pre qty in day]) [pre in],
        sum([pre qty out day]) [pre out],        
        sum([sc0 qty in day]) [sc0 in],
        sum([sc0 qty out day]) [sc0 out],
        sum([sc1 qty in day]) [sc1 in],
        sum([sc1 qty out day]) [sc1 out],
        sum([pt1 qty in day]) [pt1 in],
        sum([pt1 qty out day]) [pt1 out],
        sum([ds1 qty in day]) [ds1 in],
        sum([ds1 qty out day]) [ds1 out],
        sum([fgd qty in day]) [fgd in],
        sum([fgd qty out day]) [fgd out],
        sum([pre2 qty in day]) [pre2 in],
        sum([pre2 qty out day]) [pre2 out]
       from( 
SELECT  jo.[Line Code],
        jo.[Bucket],
        jo.[Po No],
        jo.[Po Item],
        substring(jo.[output product code],8,10) [Style],
        min(jo.[ogac]) [Ogac],
        sum(jo.[qty]) [Line_Qty],
        sum(isnull(tbst1.[st1 qty in day], 0)) [st1 qty in day],
        sum(isnull(tbst1.[st1 qty out day], 0)) [st1 qty out day],
        sum(isnull(tbas1.[as1 qty in day], 0)) [as1 qty in day],
        sum(isnull(tbas1.[as1 qty out day], 0)) [as1 qty out day],
        sum(isnull(tbcp1.[cp1 qty in day], 0)) [cp1 qty in day],
        sum(isnull(tbcp1.[cp1 qty out day], 0)) [cp1 qty out day],
        sum(isnull(tbpre.[pre qty in day], 0)) [pre qty in day],
        sum(isnull(tbpre.[pre qty out day], 0)) [pre qty out day],
        sum(isnull(tbpre2.[pre2 qty in day], 0)) [pre2 qty in day],
        sum(isnull(tbpre2.[pre2 qty out day], 0)) [pre2 qty out day],
        sum(isnull(tbsc0.[sc0 qty in day], 0)) [sc0 qty in day],
        sum(isnull(tbsc0.[sc0 qty out day], 0)) [sc0 qty out day],
        sum(isnull(tbsc1.[sc1 qty in day], 0)) [sc1 qty in day],
        sum(isnull(tbsc1.[sc1 qty out day], 0)) [sc1 qty out day],
        sum(isnull(tbpt1.[pt1 qty in day], 0)) [pt1 qty in day],
        sum(isnull(tbpt1.[pt1 qty out day], 0)) [pt1 qty out day],
        sum(isnull(tbds1.[ds1 qty in day], 0)) [ds1 qty in day],
        sum(isnull(tbds1.[ds1 qty out day], 0)) [ds1 qty out day],
        sum(isnull(tbfgd.[fgd qty in day], 0)) [fgd qty in day],
        sum(isnull(tbfgd.[fgd qty out day], 0)) [fgd qty out day]
        from [job order] jo 
        left join(
            select  jo.[jo id],SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [st1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [st1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-ST1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
        )tbst1 on jo.[jo id] = tbst1.[jo id] 
        left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [as1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [as1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-AS1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbas1 on jo.[jo id] = tbas1.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [cp1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [cp1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-CP1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbcp1 on jo.[jo id] = tbcp1.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [pre qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [pre qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-PRE' 
            and substring(jo.[output product code],1,6) = 'SA.CUT'
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbpre on jo.[jo id] = tbpre.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [pre2 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [pre2 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-PRE' 
            and substring(jo.[output product code],1,6) = 'SA.SUB'
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbpre2 on jo.[jo id] = tbpre2.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [sc0 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [sc0 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-SC0' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbsc0 on jo.[jo id] = tbsc0.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [sc1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [sc1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-SC1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbsc1 on jo.[jo id] = tbsc1.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [pt1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [pt1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-PT1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbpt1 on jo.[jo id] = tbpt1.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [ds1 qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [ds1 qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-DS1' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbds1 on jo.[jo id] = tbds1.[jo id]
         left join(
            select  jo.[jo id], SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [fgd qty in day], 
            SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [fgd qty out day]
            from 
            (select * from [Job Order])jo 
            join (select * 
            from [barcode scan] 
            where convert(varchar, [date scan], 111) <= convert(varchar, DATEADD(day,0,'".$date."'), 111)
            )bs 
            on jo.[jo id] = bs.[jo id] 
            where [department code] = '121-FGD' 
            group by jo.[jo id], jo.[line code],jo.[bucket],jo.[po no],jo.[po item],substring(jo.[output product code],8,10),jo.[output product name],                  substring(jo.[JO PPIC],10,3),jo.[ogac]
         )tbfgd on jo.[jo id] = tbfgd.[jo id]
         where 1=1 
         and jo.[bucket] between '".$bucket_from."'/*from_bucket*/ and '".$bucket_to."'/*to_bucket*/ 
         group by jo.[line code],
         jo.[bucket],
         jo.[po no],
         jo.[po item],
         substring(jo.[output product code],1,17),
         substring(jo.[output product code],8,10)
        )tbl_ok
        left join(
           select substring([output product code],8,10) [Style],max([output product name])[Description],substring([output product code],4,3)[Gender] 
           from [job order] 
           where substring([output product code],1,2) = 'FG' 
           --and substring([output product code],8,10) = 'AT7978-801'
           group by substring([output product code],8,10),substring([output product code],4,3)
        )tbl_style
        on tbl_ok.[Style] = tbl_style.[Style] 
        group by [line code],[bucket],[po no],[po item],tbl_ok.[style],[description],[gender]";
              $result = $this->sqlsrv->query($query);
              $data = array();
              while ($value = $result->fetch(PDO::FETCH_NUM)) {
                  $data[] = [
                      "line_code" => $value[0],
                      "bucket" => $value[1],
                      "po_no" => $value[2],
                      "po_item" => $value[3],
                      "style" => $value[4],
                      "desc" => $value[5],
                      "gender" => $value[6],
                      "ogac" => $value[7],
                      "line_qty" => $value[8],
                      "cp1_in" => $value[13],
                      "cp1_in_bl" => $value[13]-$value[8],
                      "cp1_out" => $value[14],
                      "cp1_out_bl" => $value[14]-$value[8],
                      "sc0_in" => $value[17],
                      "sc0_in_bl" => $value[17]-$value[8],
                      "sc0_out" => $value[18],
                      "sc0_out_bl" => $value[18]-$value[8],
                      "sc1_in" => $value[19],
                      "sc1_in_bl" => $value[19]-$value[8],
                      "sc1_out" => $value[20],
                      "sc1_out_bl" => $value[20]-$value[8],
                      "pre_in" => $value[15],
                      "pre_in_bl" => $value[15]-$value[8],
                      "pre_out" => $value[16],
                      "pre_out_bl" => $value[16]-$value[8],
                      "pre2_in" => $value[27],
                      "pre2_in_bl" => $value[27]-$value[8],
                      "pre2_out" => $value[28],
                      "pre2_out_bl" => $value[28]-$value[8],
                      "st1_in" => $value[9],
                      "st1_in_bl" => $value[9]-$value[8],
                      "st1_out" => $value[10],
                      "st1_out_bl" => $value[10]-$value[8],
                      "pt1_in" => $value[21],
                      "pt1_in_bl" => $value[21]-$value[8],
                      "pt1_out" => $value[22],
                      "pt1_out_bl" => $value[22]-$value[8],
                      "ds1_in" => $value[23],
                      "ds1_in_bl" => $value[23]-$value[8],
                      "ds1_out" => $value[24],
                      "ds1_out_bl" => $value[24]-$value[8],
                      "as1_in" => $value[11],
                      "as1_in_bl" => $value[11]-$value[8],
                      "as1_out" => $value[12],
                      "as1_out_bl" => $value[12]-$value[8],
                      "fgd_in" => $value[25],
                      "fgd_in_bl" => $value[25]-$value[8],
                      "fgd_out" => $value[26],
                      "fgd_out_bl" => $value[26]-$value[8],
                  ];
              }
              return $data;  

    }

}

?>