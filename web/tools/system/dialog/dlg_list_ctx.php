<?php

require_once("../../../../config/session.inc.php");
require_once("../../../common/mi_comm.php");
require_once("../../../common/cfg_comm.php");
require_once("lib/functions.inc.php");
require_once("template/dialog_table.inc.php");
session_load();
global $state_values;

csrfguard_validate();

$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
if (isset($_GET["callid"]))
	$callid = $_GET["callid"];
elseif (isset($_POST["callid"]))
	$callid = $_POST["callid"];
else
	$callid = null;
if (isset($_GET["from_tag"]))
	$from_tag = $_GET["from_tag"];
elseif (isset($_POST["from_tag"]))
	$from_tag = $_POST["from_tag"];
else
	$from_tag = null;

if ($callid) {
	$params = array("callid"=>$callid);
	if ($from_tag != null)
		$params["from_tag"] = $from_tag;
	$message=mi_command("dlg_list_ctx", $params, $mi_connectors[0], $errors);

	unset($dlg);
	if (!is_null($message)) {
		$dlg = $message["Dialog"];
	} else {
		$dlg = null;
	}
}
?>


<fieldset style="height:auto">
	<legend>
<?php
       	if ($callid)
		echo "Call-ID " . $callid;
	else 
		echo "Dialog no longer found!";
?>
	</legend>
<br>
<table class="test" width="300" cellspacing="2" cellpadding="2" border="0">
<?php 
	if (!$dlg) {
		echo '<tr><td align="center"><div class="formError">Unknown dialog!</div></td></tr>';
	} else {
		echo '<tr><td class="searchRecord"><div style="overflow:auto"><pre id="json">'.str_replace("\\r\\n", "\r\n", json_encode($dlg, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)).'</pre></div></td></tr>';
	}
?>
</table>
</fieldset>
