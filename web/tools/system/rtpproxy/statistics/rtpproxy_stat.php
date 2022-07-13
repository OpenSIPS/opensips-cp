<?php
require_once(__DIR__."/../../smonitor/template/stat_class.php");

class rtpproxy_stat extends custom_statistic
{
	public $socket;
	public $rtp_stat;
    
    function __construct($input) {
        parent::__construct("Rtpproxy_stat_name", "dialog_stats1", "RTP_PROXY", $input);
		$this->socket = $input['socket_id'];
		$this->rtp_stat = $input['rtp_stat_id'];
    }
    
    public static function get_description() {
        $desc = "This class gets the rtpproxy details";
        return $desc;
    }

    public static function get_name() {
        $name = "RTPPROXY_Statistic";
        return $name;
    }

    public static function get_tool() {
        $desc = "RTPProxy";
        return $desc;
    }
    
    function get_statistics() {
		require(__DIR__."/../../rtpproxy/lib/db_connect.php");
		$res = [];
		$table=get_settings_value_from_tool("table_rtpproxy", "rtpproxy");
		$sql_command = "select * from ".$table." where (1=1) order by id asc";
		$stm = $link->prepare($sql_command);
		if ($stm->execute() === false)
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $rtpproxy_socket) {
			if ($rtpproxy_socket['id'] == $this->socket)
				$selected_sock = $rtpproxy_socket['rtpproxy_sock'];
		}

		preg_match('/(?<pr>(udp|tcp)):(?<ip>.*):(?<port>\d+)/', $selected_sock, $matches);

		$fp = fsockopen($matches['pr']."://".$matches['ip'], $matches['port'],  $errno, $errstr,5);
		if (!$fp) echo($errstr);
	
		else {
			$out = "1124 I";
			fwrite($fp, $out);
			$line = fgets($fp);
			preg_match('/(.*): (?<n>\d+)(.*?)/', $line, $matches);
			$res['sessions_created'] = $matches['n'];
			$line = fgets($fp);
			preg_match('/(.*): (?<n>\d+)(.*?)/', $line, $matches);
			$res['active_sessions'] = $matches['n'];
			$line = fgets($fp);
			preg_match('/(.*): (?<n>\d+)(.*?)/', $line, $matches);
			$res['active_streams'] = $matches['n'];
			fclose($fp);
		}

        return $res[$this->rtp_stat];
    }

    public static function new_form($params = null) {
		form_generate_input_text("Custom statistic name:", null, "name_id", "y", $params['name_id'], null, null);
		form_generate_input_text("Socket:", null, "socket_id", "y", $params['socket_id'], null, null);
		form_generate_input_text("Stat:", null, "rtp_stat_id", "y", $params['rtp_stat_id'], null, null);
	}
}


?>