<?php

class Qms_DB{

    private static $INSTANCE = null;
    private $mysql,
            $HOST = '10.50.171.20',
            $USER = 'aas',
            $PASS = 'aas',
            $DBNAME = 'db_ads';

    public function __construct(){
        try{
            $this->mysql = new PDO("mysql:host=$this->HOST;dbname=$this->DBNAME", $this->USER, $this->PASS);
            $this->mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Berhasil koneksi ke Database";
            
            }
        catch(PDOException $e) {
            die("Error connecting to MySQL: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if(!isset(self::$INSTANCE)){
          self::$INSTANCE = new Qms_DB();
        }
          return self::$INSTANCE;
    }

    public function qms_data($date,$line,$dept){
      // switch ($line) {
      //   case 'A1':
      //       $gd = 'Line1';
      //     break;
      //   case 'A2':
      //       $gd = 'Line2';
      //     break;
      //   case 'A3':
      //       $gd = 'Line3';
      //     break;
      //   case 'A4':
      //       $gd = 'Line4';
      //     break;
      //   case 'A5':
      //       $gd = 'Line5';
      //     break;
      //   case 'A6':
      //       $gd = 'Line6';
      //     break;        
      //   default:
      //       $gd = 'Line7';
      //     break;
      // }

      if ($dept == '121-AS1') {
        $query = "SELECT ncvs, style, SUM(pass) pass,SUM(rework) rework, tanggal from(
                  SELECT ncvs,style, CASE WHEN status1= 'rework' THEN 1 ELSE 0 END rework,CASE WHEN status1= 'pass' THEN 1 ELSE 0 END pass,tanggal
                  FROM qcline where ncvs = '".$line."' AND tanggal = '".$date."' 
                  )tbl
                  GROUP BY ncvs,tanggal";
      }elseif($dept == '121-ST1'){
        $query = "SELECT ncvs, style, SUM(pass) pass,SUM(rework) rework, tanggal from(
                  SELECT ncvs,style, CASE WHEN status1= 'rework' THEN 1 ELSE 0 END rework,CASE WHEN status1= 'pass' THEN 1 ELSE 0 END pass,tanggal
                  FROM qcline_stc where ncvs = '".$line."' AND tanggal = '".$date."' 
                  )tbl
                  GROUP BY ncvs,tanggal";
      }else{
        $query="SELECT ncvs, style, SUM(pass) pass,SUM(rework) rework, tanggal from(
                  SELECT ncvs,style, CASE WHEN status1= 'rework' THEN 0 ELSE 0 END rework,CASE WHEN status1= 'pass' THEN 0 ELSE 0 END pass,tanggal
                  FROM qcline_stc where ncvs = '".$line."' AND tanggal = '".$date."' 
                  )tbl
                  GROUP BY ncvs,tanggal";
      }
      
      $result = $this->mysql->query($query);
      $data = array();
      while ($value = $result->fetch(PDO::FETCH_NUM)) {
        $data[] = [
          "ncvs" => $value[0],
          "style" => $value[1],
          "pass" => $value[2],
          "rework" => $value[3],
          "tanggal" => $value[4]
        ];
      }
      return $data;
    }

    public function rework_data($date,$line,$dept){

      // switch ($line) {
      //   case 'A1':
      //       $gd = 'Line1';
      //     break;
      //   case 'A2':
      //       $gd = 'Line2';
      //     break;
      //   case 'A3':
      //       $gd = 'Line3';
      //     break;
      //   case 'A4':
      //       $gd = 'line4';
      //     break;
      //   case 'A5':
      //       $gd = 'Line5';
      //     break;
      //   case 'A6':
      //       $gd = 'Line6';
      //     break;        
      //   default:
      //       $gd = 'Line1';
      //     break;
      // }

      if ($dept == '121-AS1') {
        $query = "SELECT style, count(status1), status2 
                  FROM qcline
                  WHERE status1 = 'rework' 
                  AND tanggal = '".$date."'
                  AND ncvs = '".$line."'
                  GROUP BY status2";
      }else{
        $query = "SELECT style, count(status1), status2 
                  FROM qcline_stc
                  WHERE status1 = 'rework' 
                  AND tanggal = '".$date."'
                  AND ncvs = '".$line."'
                  GROUP BY status2";
      }

      $result = $this->mysql->query($query);
      $data = array();
      while ($value = $result->fetch(PDO::FETCH_NUM)) {
        $data[] = [
          "style" => $value[0],
          "jml_rework" => $value[1],
          "desc" => $value[2]
        ];
      }
      return $data;
    }

    public function qms_asy($line,$date){
      $query = "SELECT ncvs, style, SUM(pass) pass,SUM(rework) rework, tanggal from(
                  SELECT ncvs,style, CASE WHEN status1= 'rework' THEN 1 ELSE 0 END rework,CASE WHEN status1= 'pass' THEN 1 ELSE 0 END pass,tanggal
                  FROM qcline where ncvs = '".$line."' AND tanggal = '".$date."' 
                  )tbl
                  GROUP BY ncvs,tanggal";
      $result = $this->mysql->query($query);
      $data = array();
      while ($value = $result->fetch(PDO::FETCH_NUM)) {
        $data[] = [
          "ncvs" => $value[0],
          "style" => $value[1],
          "pass" => $value[2],
          "rework" => $value[3],
          "tanggal" => $value[4]
        ];
      }
      return $data;
    }
}