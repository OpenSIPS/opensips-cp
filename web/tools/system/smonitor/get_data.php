<?php
	session_start();
    require_once("../../../../config/tools/system/smonitor/db.inc.php");
    require_once("../../../../config/db.inc.php");
	require("../../../../config/tools/system/smonitor/local.inc.php");
    
    $dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
    try {
        $link = new PDO($dsn, $config->db_user, $config->db_pass);
    } catch (PDOException $e) {
        error_log(print_r("Failed to connect to: ".$dsn, true));
        print "Error!: " . $e->getMessage() . "<br/>";
        die;
    }

    $stat = $_GET['stat'];
    $fstat = $_GET['full_stat'];
    $box = $_GET['box'];
	$row = $_SESSION[$stat];
    $sampling_time = $_SESSION['stime'];
    $vals ="";
    $vals.="date,value";
    $index = $_SESSION['csize'];

    $sql = "SELECT * FROM ".$config->table_monitoring." WHERE name = ? AND box_id = ? ORDER BY time DESC LIMIT ".$index;
    $stm = $link->prepare($sql);
	$stm->execute(array($fstat, $box));
    $row = $stm->fetchAll(PDO::FETCH_ASSOC);
    $last = $row[0]['time'];
    $sum = 0;
    foreach ($row as $r){
        if ($index > 0) {
            $d = date("U", substr($r['time'], 0, 10));
            if (($last - intval($d)) / 60 >$sampling_time * 1.5) $r['value'] = null;
            $index--;
            if ($r['value'] == null) $r['value'] = "f";
            $vals.="\n".date("Y-m-d", substr($r['time'], 0, 10));
            $vals.=",".$r['value'];
            $last = intval($d);
        }
    }
    echo($vals);
    ?>
