<?php
require("../../../common/mi_comm.php");
require_once("lib/functions.inc.php");
require_once("../../../../config/tools/users/user_management/local.inc.php");


function secs2hms($secs) {
	if ($secs<0) return false;
	$m = (int)($secs / 60); $s = $secs % 60;
	$h = (int)($m / 60); $m = $m % 60;
	
	$time = "";


	if ($h>0){
		$hh="";
		if ($h<10)
			$hh.="0".$h;
		else 
			$hh.=$h;
		$time.$hh.":";
	}
	

	if ($m>0){
		$mm="";
		if ($m<10)
			$mm.="0".$m;
		else
		    $mm.=$m;
	    $time.=$mm.":";
	}

	if ($h>0 && $m==0)
		$time.="00:";

	if ($s>=0){
        $ss="";
        if ($s<10 && $m>0)
	        $ss.="0".$s;
	    else
		    $ss.=$s;
		$time.=$ss;
	}

	

//	$arr = array($h, $m, $s);
	return $time;
}

if ($_GET['action']=="delcon"){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	for ($i=0;$i<count($mi_connectors);$i++){
	    $comm_type=params($mi_connectors[$i]);
	    $comm = "ul_rm_contact location ".$_GET["username"]."@".$_GET["domain"]." ".$_GET["contact"];
	    $mess=mi_command($comm,$errors,$status);
	    print_r($errors);
	    $status = trim($status);
	}
}

$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
for ($i=0;$i<count($mi_connectors);$i++){
	$comm_type=params($mi_connectors[$i]);
    $comm = "ul_show_contact location ".$_GET["username"]."@".$_GET["domain"];
    $message=mi_command($comm,$errors,$status);
//    print_r($errors);
    $status = trim($status);
}
unset($contact);
if ($message == NULL) {
	echo "The user is not registered!";
}
else{
	$stupidtags = array("&lt;","&gt;");
	$goodtags = array("<",">");

	$message=str_replace($stupidtags,$goodtags,$message);

	if ($comm_type != "json"){


		preg_match_all('/Contact:: <.+?>/', $message, $mcontact);
		for ($i=0;$i<count($mcontact[0]);$i++){
			$contact[$i] = substr($mcontact[0][$i],11,-1);
			if ($contact[$i]=="") $contact[$i]="n/a";
		}
		preg_match_all('/q=.?;/', $message, $mq);
		for ($i=0;$i<count($mq[0]);$i++){
			$q[$i]=substr($mq[0][$i],2,-1);
			if ($q[$i]=="") $q[$i]="n/a";
		}

		preg_match_all('/expires=.+?;/', $message, $mexpires);
		for ($i=0;$i<count($mexpires[0]);$i++){
			$expires[$i]=secs2hms(substr($mexpires[0][$i],8,-1));
		}
		preg_match_all('/flags=.+?;/', $message, $mflags);
		for ($i=0;$i<count($mflags[0]);$i++){
			$flags[$i]=decbin(hexdec(substr($mflags[0][$i],6,-1)));
		}
	
		preg_match_all('/cflags=.+?;/', $message, $mcflags);
		for ($i=0;$i<count($mcflags[0]);$i++){
			$cflags[$i]=decbin(hexdec(substr($mcflags[0][$i],7,-1)));
		}

		preg_match_all('/socket=<.+?>/', $message, $msocket);
		for ($i=0;$i<count($msocket[0]);$i++){
			$socket[$i] = substr($msocket[0][$i],8,-1);
			if ($socket[$i]=="") $socket[$i]="n/a";
		}

		preg_match_all('/methods=.+?;/', $message, $mmethods);
		for ($i=0;$i<count($mmethods[0]);$i++){
			$methods[$i] = decbin(hexdec(substr($mmethods[0][$i],8,-1)));
		}

		preg_match_all('/received=<.+?>/', $message, $mreceived);
		for ($i=0;$i<count($mreceived[0]);$i++){
			$received[$i]=substr($mreceived[0][$i],10,-1);
			if ($received[$i]=="") $received[$i]="n/a";	
		}

		preg_match_all('/user_agent=<.+?>/', $message, $museragent);
		for ($i=0;$i<count($museragent[0]);$i++){
			$useragent[$i]=substr($museragent[0][$i],12,-1);
			if ($useragent[$i]=="") $useragent[$i]="n/a";
		}
	}
	else {
		$message = json_decode($message,true);
		$message = $message['Contact'];
		for ($j=0; $j <= count($message); $j++){
			preg_match_all('/^<sip:.+?>/', $message[$j]['value'], $mcontact);
			for ($i=0;$i<count($mcontact[0]);$i++){
				$contact[$j] = substr($mcontact[0][$i],1,-1);
				if ($contact[$j]=="") $contact[$i]="n/a";
			}
			preg_match_all('/q=.?;/', $message[$j]['value'], $mq);
			for ($i=0;$i<count($mq[0]);$i++){
				$q[$j]=substr($mq[0][$i],2,-1);
				if ($q[$j]=="") $q[$j]="n/a";
			}

			preg_match_all('/expires=.+?;/', $message[$j]['value'], $mexpires);
			for ($i=0;$i<count($mexpires[0]);$i++){
				$expires[$j]=secs2hms(substr($mexpires[0][$i],8,-1));
			}
			preg_match_all('/flags=.+?;/', $message[$j]['value'], $mflags);
			for ($i=0;$i<count($mflags[0]);$i++){
				$flags[$j]=decbin(hexdec(substr($mflags[0][$i],6,-1)));
			}	
	
			preg_match_all('/cflags=.+?;/', $message[$j]['value'], $mcflags);
			for ($i=0;$i<count($mcflags[0]);$i++){
				$cflags[$j]=decbin(hexdec(substr($mcflags[0][$i],7,-1)));
			}

			preg_match_all('/socket=<.+?>/', $message[$j]['value'], $msocket);
			for ($i=0;$i<count($msocket[0]);$i++){
				$socket[$j] = substr($msocket[0][$i],8,-1);
				if ($socket[$j]=="") $socket[$j]="n/a";
			}

			preg_match_all('/methods=.+?;/', $message[$j]['value'], $mmethods);
			for ($i=0;$i<count($mmethods[0]);$i++){
				$methods[$j] = decbin(hexdec(substr($mmethods[0][$i],8,-1)));
			}

			preg_match_all('/received=<.+?>/', $message[$j]['value'], $mreceived);
			for ($i=0;$i<count($mreceived[0]);$i++){
				$received[$j]=substr($mreceived[0][$i],10,-1);
				if ($received[$j]=="") $received[$j]="n/a";	
			}

			preg_match_all('/user_agent=<.+?>/', $message[$j]['value'], $museragent);
			for ($i=0;$i<count($museragent[0]);$i++){
				$useragent[$j]=substr($museragent[0][$i],12,-1);
				if ($useragent[$j]=="") $useragent[$j]="n/a";
			}
		}
	}
}

?>
<html>
<head>
<title>Contacts for <?php echo $_GET["username"];?></title>
<link href="style/style.css" type="text/css" rel="StyleSheet">
</head>
<body>
<table width="100%" cellspacing="1" cellpadding="2" border="3">
<tr align="left">
        <td class="listTitle">This user has <?php echo count($contact);?> contact(s) registerd.</td>
</tr>
<?php 
	for ($i=0;$i<count($contact);$i++){
?>
<tr align="center">
<form name="delcontact" action="contacts.php" method="GET">
<input type="hidden" name="action" value="delcon" />
<input type="hidden" name="username" value="<?=$_GET["username"]?>" />
<input type="hidden" name="domain" value="<?=$_GET["domain"]?>" />
<table width="100%" cellspacing="2" cellpadding="2" border="2">
	<tr align="left">
		<td class="listContactTitle" width="70">Contact</td>
		<td class="rowEven"><?=$contact[$i];?></td>
		<input type="hidden" name="contact" value="<?=$contact[$i]?>" />
	</tr>
	<tr align="left">
		<td class="listContactTitle" width="70">Priority</td>
		<td class="rowOdd"><?=$q[$i];?></td>
	</tr>
	<tr align="left">
        <td class="listContactTitle" width="70">Expires</td>
        <td class="rowEven"><?=$expires[$i];?></td>
    </tr>
	<tr align="left">
        <td class="listContactTitle" width="70">Flags</td>
        <td class="rowOdd"><?=$flags[$i];?></td>
    </tr>
	<tr align="left">
        <td class="listContactTitle" width="70">CFlags</td>
        <td class="rowEven"><?=$cflags[$i];?></td>
    </tr>
	<tr align="left">
        <td class="listContactTitle" width="70">Socket</td>
        <td class="rowOdd"><?=$socket[$i];?></td>
    </tr>
	<tr align="left">
        <td class="listContactTitle" width="70">Methods</td>
        <td class="rowEven"><?=$methods[$i];?></td>
    </tr>	
	<tr align="left">
        <td class="listContactTitle" width="70">Received</td>
        <td class="rowOdd"><?=$received[$i];?></td>
    </tr>
	<tr align="left">
        <td class="listContactTitle" width="70">User Agent</td>
        <td class="rowEven"><?=$useragent[$i];?></td>
    </tr>
	<tr align="center">
		<td class="listTitle" width="100%" colspan="2"><input type="submit" class="delconButton" value="Delete Contact" /> </td>
	</tr>
</table>
</form>
</tr>
	<?php 
	}
	?>
</table>
</body>
</html>
