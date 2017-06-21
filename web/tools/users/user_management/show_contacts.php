<?php

require_once("../../../../config/tools/users/user_management/local.inc.php");
require_once("../../../common/mi_comm.php");
require_once("../../../common/cfg_comm.php");
require_once("lib/functions.inc.php");


$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
$comm = "ul_show_contact location ".$_GET["username"]."@".$_GET["domain"];
$message=mi_command($comm,$mi_connectors[0], $errors,$status);
$status = trim($status);

unset($contact);
if ($message != NULL) {

	$stupidtags = array("&lt;","&gt;");
	$goodtags = array("<",">");
	$message=str_replace($stupidtags,$goodtags,$message);

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
