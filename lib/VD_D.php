<?php

class Vd_d{

    private static $INSTANCE = null;
    private $sqlsrv,
            $HOST = '10.50.171.17',
            $USER = 'J2Data',
            $PASS = 'J2QWER19@',
            $DBNAME = 'XCERP';

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
          self::$INSTANCE = new Vd_d();
        }
          return self::$INSTANCE;
    }

    //GET QMS DATA
    public function qms_data($date,$line,$dept){

        $process = '';
        switch ($dept) {
                case '121-ST1':
                       $process = 'Stiching';
                       break;
                case '121-AS1':
                       $process = 'Assembly';
                       break;
                   
                   default:
                       $process = '';
                       break;
               }       

        $query = "SELECT [Dept], [Process], SUM([ActualQty]), SUM([Rework]) from [VD_QCData]
                    WHERE [Dept] = '".$line."'
                    AND [Process] = '".$process."'
                    AND convert(varchar,[QCDate],23) = '".$date."'
                    GROUP BY [Dept], [Process]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            
            $data[] = [
                "dept" => $value[0],
                "process" => $value[1],
                "pass" => $value[2],
                "rework" => $value[3]
            ];
        }

        return $data;
    }

    //GET MP ACTUAL
    public function mp_actual($date,$line,$dept){
        $process = '';
        switch ($dept) {
                case '121-ST1':
                       $process = 'Stiching';
                       break;
                case '121-AS1':
                       $process = 'Assembly';
                       break;
                   
                   default:
                       $process = '';
                       break;
               }       


        $query = "SELECT [Dept], [Process], [Actual], [TotalPeople] 
                    FROM [VD_HRData]
                    WHERE [Dept] = '".$line."'
                    AND [Process] = '".$process."'
                    AND convert(varchar,[ProductionDate],23) = '".$date."'
                    GROUP BY [Dept], [Process], [Actual], [TotalPeople]";
        $result = $this->sqlsrv->query($query);
        $data = array();
        while ($value = $result->fetch(PDO::FETCH_NUM)) {
            
            $data[] = [
                "dept" => $value[0],
                "process" => $value[1],
                "actual" => round($value[2]),
                "total_people" => round($value[3])
            ];
        }

        return $data;
    }
    
}

?>