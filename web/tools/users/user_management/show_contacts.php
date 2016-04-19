<?php

require_once("../../../../config/tools/users/user_management/local.inc.php");
require_once("../../../common/mi_comm.php");
require_once("lib/functions.inc.php");


$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
for ($i=0;$i<count($mi_connectors);$i++){
	$comm_type=mi_get_conn_params($mi_connectors[$i]);
    $comm = "ul_show_contact location ".$_GET["username"]."@".$_GET["domain"];
    $message=mi_command($comm,$errors,$status);
    $status = trim($status);
}
unset($contact);
if ($message != NULL) {
	$stupidtags = array("&lt;","&gt;");
	$goodtags = array("<",">");

	$message=str_replace($stupidtags,$goodtags,$message);
	if ($comm_type != "json"){

		preg_match_all('/AOR:: .*/', $message, $maor);
		$aor = substr($maor[0][0],6);

		preg_match_all('/Contact:: .*?\s/', $message, $mcontact);
		for ($i=0;$i<count($mcontact[0]);$i++){
			$contact[$i] = substr($mcontact[0][$i],10,-1);
			if ($contact[$i]=="") $contact[$i]="n/a";
		}
		preg_match_all('/Q=.?\n/', $message, $mq);
		for ($i=0;$i<count($mq[0]);$i++){
			$q[$i]=substr(trim($mq[0][$i]),2);
			if ($q[$i]=="") $q[$i]="n/a";
		}

		preg_match_all('/Expires::.+?\n/', $message, $mexpires);
		for ($i=0;$i<count($mexpires[0]);$i++){
			$exp = substr($mexpires[0][$i],10,-1);
			if (is_numeric($exp)){
				$expires[$i]=secs2hms($exp);
			}
			else {
				$expires[$i]=$exp;
			}
		}
		preg_match_all('/Flags::.+?\n/', $message, $mflags);
		for ($i=0;$i<count($mflags[0]);$i++){
			$flags[$i]=decbin(hexdec(substr($mflags[0][$i],6,-1)));
		}
	
		preg_match_all('/Cflags::.+?\n/', $message, $mcflags);
		for ($i=0;$i<count($mcflags[0]);$i++){
			$cflags[$i]=decbin(hexdec(substr($mcflags[0][$i],7,-1)));
		}

		preg_match_all('/Socket::.+?\n/', $message, $msocket);
		for ($i=0;$i<count($msocket[0]);$i++){
			$socket[$i] = substr($msocket[0][$i],8,-1);
			if ($socket[$i]=="") $socket[$i]="n/a";
		}

		preg_match_all('/Methods::.+?\n/', $message, $mmethods);
		for ($i=0;$i<count($mmethods[0]);$i++){
			if (substr($mmethods[0][$i],10) <= 65535){
				$methods[$i] = decbin(hexdec(substr($mmethods[0][$i],8,-1)));
			}
			else if (substr($mmethods[0][$i],10) == 4294967295){
				$methods[$i] = "all methods are accepted";
			}	
			else {
				$methods[$i] = "invalid methods";
			}
		}

		preg_match_all('/Received::.+?\n/', $message, $mreceived);
		for ($i=0;$i<count($mreceived[0]);$i++){
			$received[$i]=substr($mreceived[0][$i],10,-1);
			if ($received[$i]=="") $received[$i]="n/a";	
		}
		
		preg_match_all('/State::.+?\n/', $message, $mstate);
		for ($i=0;$i<count($mstate[0]);$i++){
			$state[$i] = substr($mstate[0][$i],8,-1);
			if ($state[$i]=="") $state[$i]="n/a";
		}

		preg_match_all('/User-agent::.+?\n/', $message, $museragent);
		for ($i=0;$i<count($museragent[0]);$i++){
			$useragent[$i]=substr($museragent[0][$i],12,-1);
			if ($useragent[$i]=="") $useragent[$i]="n/a";
		}
	}
	else {
		$message = json_decode($message,true);
		$aor = $message['AOR'][0]['value'];
		$message = $message['AOR'][0]['children']['Contact'];
		for ($j=0; $j < count($message); $j++){
			$contact[$j] = $message[$j]['value'];

			$q[$j]=$message[$j]['attributes']['Q'];
			if ($q[$j]=="") $q[$j]="n/a";

			$exp=$message[$j]['children']['Expires'];
			if (is_numeric($exp)){
				$expires[$j]=secs2hms($exp);
			}
			else {
				$expires[$j]=$exp;
			}	
	
			$flags[$j]	= decbin(hexdec($message[$j]['children']['Flags']));
			
			$socket[$j] = $message[$j]['children']['Socket'];
			if ($socket[$j]=="") $socket[$j]="n/a";

			$methods[$j] = decbin(hexdec($message[$j]['children']['Methods']));
			if ($methods[$j] <= 65535){
				$methods[$j] = decbin(hexdec($methods[$j]));
			}
			else if ($methods[$j] == 4294967295){
				$methods[$j] = "all methods are accepted";
			}	
			else {
				$methods[$j] = "invalid methods";
			}

			$received[$j] = $message[$j]['children']['Received'];
			if ($received[$j]=="") $received[$j]="n/a";
			
			$state[$j] = $message[$j]['children']['State'];
			if ($state[$j]=="") $state[$j]="n/a";

			$useragent[$j] = $message[$j]['children']['User-agent'];
			if ($useragent[$j]=="") $useragent[$j]="n/a";
		}
	}
}
?>


<fieldset>
	<legend>
	<?php if (isset($aor) && $aor != ""){
		$regc = count($contact);
		$s = ($regc == 1)?"":"s";
		echo "User ".$aor." has<br>".count($contact)." contact".$s." registered";
	}
	else {
		echo "User ".$_GET['username']."@".$_GET['domain']." has <br>0 registered contacts";
	}
	?>
	</legend>
<br>
<article class="tabs">
<?php 
	for ($i=0;$i<count($contact);$i++){
?>
<section id="tab<?=$i+1?>">
<h2><a href="#tab<?=$i+1?>">Contact <?=$i+1?></a></h2>
		<table class="test" width="300" cellspacing="2" cellpadding="2" border="0">
			<tr align="left">
				<td class="searchRecord" width="70" style="width: 70px;">Contact</td>
				<td class="searchRecord" style="width: 230px;"><?=str_replace(";",";<br>",$contact[$i]);?></td>
				<input type="hidden" name="contact" value="<?=$contact[$i]?>" />
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">QValue</td>
				<td class="searchRecord"><?=$q[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">Expires</td>
				<td class="searchRecord"><?=$expires[$i]; ?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">Flags</td>
				<td class="searchRecord"><?=$flags[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">CFlags</td>
				<td class="searchRecord"><?=$cflags[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">Socket</td>
				<td class="searchRecord"><?=$socket[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">Methods</td>
				<td class="searchRecord"><?=$methods[$i];?></td>
			</tr>	
			<tr align="left">
				<td class="searchRecord" width="70">Received</td>
				<td class="searchRecord"><?=$received[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">State</td>
				<td class="searchRecord"><?=$state[$i];?></td>
			</tr>
			<tr align="left">
				<td class="searchRecord" width="70">User Agent</td>
				<td class="searchRecord"><?=$useragent[$i];?></td>
			</tr>
			<tr align="center">
				<td class="listTitle" width="100%" colspan="2">
					<form name="delcontact" action="#" method="POST">
							<input type="hidden" name="action" value="delcon"/>
							<input type="hidden" name="username" value="<?= $_GET["username"] ?>"/>
							<input type="hidden" name="domain" value="<?= $_GET["domain"] ?>"/>
							<input type="hidden" name="contact" value="<?= $contact[$i] ?>"/>
							<input type="submit" class="formButton" value="Delete Contact"/>
					</form>	
				</td>
			</tr>
		</table>
</section>
	<?php 
	}
	if (!isset($contact) || count($contact) == 0){
	?>
	<div class="nocontacts">
	</div>
	<?php
	}
	?>
</article>
</fieldset>
