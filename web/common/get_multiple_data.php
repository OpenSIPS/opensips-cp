<?php
	session_start();
    require_once("../../../../config/tools/system/smonitor/db.inc.php");
    require_once("../../../../config/db.inc.php");
    
    $dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
    try {
        $link = new PDO($dsn, $config->db_user, $config->db_pass);
    } catch (PDOException $e) {
        error_log(print_r("Failed to connect to: ".$dsn, true));
        print "Error!: " . $e->getMessage() . "<br/>";
        die;
    }

    $statID = $_GET['statID'];
    $fstats = json_decode($_GET['full_stats']);
    
    $zoomOut = $_GET['zoomOut'];
    $box = $_GET['box'];
    $normal = json_decode($_GET['normal']);
    $sampling_time = $_SESSION['sampling_time'];
    $table_monitoring = $_SESSION['tmonitoring'];
    $vals ="";
    $vals.="date,value,name";
    $chart_size = $_SESSION['chart_size'];
    if ($zoomOut == 'true') {
        $chart_size = $_SESSION['chart_history'];
    }
    
    $vals.="\n".date("Y-m-d-H-i-s", time());
    $vals.=",f,".$fstats[0];

    foreach($fstats as $idx => $stat) {
        $sql = "SELECT * FROM ".$table_monitoring." WHERE name = ? AND box_id = ? AND time > ? ORDER BY time DESC";
        $stm = $link->prepare($sql);
        $stm->execute(array($stat, $box, time() - $chart_size * 3600));
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        if ($normal[$idx] == 0) {
            $prev = $row[0]['value'];
            for ($i = 1; $i < count($row); $i++) {
                $plot_value = $prev - $row[$i]['value'];
                if ($plot_value <= 0) $plot_value = 0;
                $prev = $row[$i]['value'];
                $row[$i - 1]['value'] = $plot_value;
            }
            array_pop($row);
        }
        $last = $row[0]['time'];
        $sum = 0;
        foreach ($row as $r){
            $d = date("U", substr($r['time'], 0, 10));
            if (($last - intval($d)) / 60 > $sampling_time * 1.5) {
                $vals.="\n".date("Y-m-d-H-i-s", substr($r['time'], 0, 10));
                $vals.=",f";
            } else {
                if (is_null($r['value'])) $r['value'] = "f";
                $vals.="\n".date("Y-m-d-H-i-s", substr($r['time'], 0, 10));
                $vals.=",".$r['value'];
            }
            $vals.=",".$stat;
            $last = intval($d);
        }
    }
    $vals.="\n".date("Y-m-d-H-i-s", time() - $chart_size * 3600);
    $vals.=",f,".$fstats[0];
    echo($vals);
    ?>
