<?php
/*
* Copyright (C) 2017 OpenSIPS Project
*
* This file is part of opensips-cp, a free Web Control Panel Application for
* OpenSIPS SIP server.
*
* opensips-cp is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* opensips-cp is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
?>

<script language="JavaScript">

function get_elements() {
	var arr=[];

	/* we need to support elements from both inputs and textareas now */
	var inputs = document.getElementsByTagName('input');
	var textareas = document.getElementsByTagName('textarea');

	for (var i = 0; i < inputs.length; i++)
		arr.push(inputs[i]);
	for (var i = 0; i < textareas.length; i++)
		arr.push(textareas[i]);
	return arr;
}

function form_init_status() {
	elem = get_elements();

	for(var i = 0; i < elem.length; i++) {
		if (elem[i].oninput)
			elem[i].oninput();
	}
}

function form_full_check() {
	elem = get_elements();
	ret = true;
	button = null;

	for(var i = 0; i < elem.length; i++) {
		if (elem[i].getAttribute("opt")!=null && elem[i].getAttribute("opt")!="") {
			if ( !(elem[i].getAttribute("opt")=="y" && elem[i].value=="") &&
				!(elem[i].getAttribute("valid")=="ok") )
				ret = false;
		} else if (button == null && elem[i].type=="submit")
			button = elem[i];
	}
	if (button!=null) {
		if ( ret )
			button.disabled = false;
		else
			button.disabled = true;
	}
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}
function validate_json(str, format) {
    try {
        JSON.parse(str);
		if (Array.isArray(JSON.parse(str)) && format == "object")
			return false;
		else if (!Array.isArray(JSON.parse(str)) && format == "array")
			return false;
    } catch (e) {
        return false;
    }
    return true;
}

function validate_input(field, output, regex, validation, format){
	val = document.getElementById(field).value;
	if (val=="") {
		if (document.getElementById(field).getAttribute("opt")=="y") {
			document.getElementById(output).innerHTML = '';
			document.getElementById(field).setAttribute("valid","ok");
			ret = 1;
		} else {
			document.getElementById(output).innerHTML = '<img src="../../../images/share/must-icon.png">';
			document.getElementById(field).setAttribute("valid","ko");
			ret = -1;
		}
	} else {
		if (regex == null || val.match(new RegExp( regex,"g")) ) {
			document.getElementById(output).innerHTML = '<img src="../../../images/share/ok_small.png">';
			document.getElementById(field).setAttribute("valid","ok");
			ret = 1;
		} else {
			document.getElementById(output).innerHTML = '<img src="../../../images/share/ko_small.png">';
			document.getElementById(field).setAttribute("valid","ko");
			ret = -1;
		}

		if (validation != null && !validation(val, format)) {
			document.getElementById(output).innerHTML = '<img src="../../../images/share/ko_small.png">';
			document.getElementById(field).setAttribute("valid","ko");
			ret = -1;
		}
	}

	form_full_check();
	return ret;
}

function validate_password(field, output, password){
	pw1 = document.getElementById(field).value;
	pw2 = document.getElementById(password).value;
	if (pw2=="") {
		if (document.getElementById(field).getAttribute("opt")=="y")
			document.getElementById(output).innerHTML = '';
		else
			document.getElementById(output).innerHTML = '<img src="../../../images/share/must-icon.png">';
		document.getElementById(field).setAttribute("valid","ko");
		ret =-1;
	} else if (pw1 == pw2) {
		document.getElementById(output).innerHTML = '<img src="../../../images/share/ok_small.png">';
		document.getElementById(field).setAttribute("valid","ok");
		ret = 1;
	} else {
		document.getElementById(output).innerHTML = '<img src="../../../images/share/ko_small.png">';
		document.getElementById(field).setAttribute("valid","ko");
		ret = -1;
	}

	form_full_check();
	return ret;
}

function readMore() {
            var dots = document.getElementById('dots');
            var moreText = document.getElementById('more');
            var btnText = document.getElementById('myBtn');
          
            if (dots.style.display === 'none') {
              dots.style.display = 'inline';
              btnText.innerHTML = 'Read more'; 
              moreText.style.display = 'none';
            } else {
              dots.style.display = 'none';
              btnText.innerHTML = 'Read less'; 
              moreText.style.display = 'inline';
            }
          }

function toggleWidgetFormat() {
	var id = "desc_";
	var dots = document.getElementById(id.concat('dots'));
	var moreText = document.getElementById(id.concat('more'));
	var btnText = document.getElementById(id.concat('myBtn'));
	
	if (dots.style.display === 'none') {
		dots.style.display = 'inline';
		btnText.innerHTML = 'Expand widget description'; 
		moreText.style.display = 'none';
	} else {
		dots.style.display = 'none';
		btnText.innerHTML = 'Hide widget description'; 
		moreText.style.display = 'inline';
	}
}

function toggleFormat(tool, id) {
            var dots = document.getElementById(id.concat('dots'));
            var moreText = document.getElementById(id.concat('more'));
            var btnText = document.getElementById(id.concat('myBtn'));
          
            if (dots.style.display === 'none') {
              dots.style.display = 'inline';
              btnText.innerHTML = 'See format for '.concat(tool); 
              moreText.style.display = 'none';
            } else {
              dots.style.display = 'none';
              btnText.innerHTML = 'Hide format'; 
              moreText.style.display = 'inline';
            }
          }
</script>

<?php
global $table_regex;
$table_regex = "^[a-zA-Z0-9_]+$";


function print_description() {
	global $config;
	$long = get_settings_value('tool_description');
	$short = substr($long, 0, 100);
	$long = substr($long, 100, strlen($long));
	echo (
	 "<style>
	  #more {display: none;}
	  </style>
	  <p class='breadcrumb'>".$short."<span id='dots'>. . .</span><span id='more' >".$long."</span></p>
	  <a href='#' onclick='readMore()' id='myBtn' class='menuItemSelect'>Read more</a>"
	);
}

function print_widget_description($desc) {
	echo (
		"<tr><td></td><td class='breadcrumb'><style>
		 #desc_more {display: none;}
		 </style>
		 ".(count(explode(" ", $desc))<=30?"":"<a href='#' onclick='toggleWidgetFormat()' id='desc_myBtn' class='exampleButton' >Expand widget description</a>")."
		 <p><span><pre style=' white-space: pre-wrap;' id='desc_dots'>".implode(" ", array_slice(explode(" ", $desc), 0, 30))."".(count(explode(" ", $desc))>30?"...":"")."</pre><pre style=' white-space: pre-wrap;' id='desc_more'>".$desc."</pre></span></p></td></tr>"
	   );
}

function print_example($example, $param, $id) {
	$param = str_replace("'", "", $param);
	$param = str_replace('"', "", $param);
	$short = "";
	echo (
		"<tr><td></td><td><style>
		 #".$id."more {display: none;}
		 </style>
		 <a href='#' onclick='toggleFormat(\"".$param."\", \"".$id."\")' id='".$id."myBtn' class='exampleButton' >See format for ".$param."</a>
		 <p >".$short."<span id='".$id."dots'></span><pre id='".$id."more' >".$example."</pre></p></td></tr>"
	   );
}

function form_generate_input_textarea($title,$tip,$id,$opt,$val,$mlen=null,$re=null,$validation=null,$json_format=null) {
	if ($val!=null)
		$valid=" valid='ok'";
	else
		$valid = "";
	if ($mlen!=null)
		$maxlen=" maxlength='".$mlen."'";
	else
		$maxlen = "";

	$validate=" opt='".$opt."' oninput='auto_grow(this);validate_input(\"".$id."\", \"".$id."_ok\",".($re?"\"".$re."\"":"null").",".$validation.",\"".$json_format."\")'";
	$pixelNo = substr_count($val, "\n") * 16 + 35;

	
	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>");
	if (!is_null($tip))
		print("			
				<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div> ");
	print("
			</td>
			<td class='dataRecord' width='250'>
				<table style='width:100%'><tr><td>
				<textarea style='height:".$pixelNo."px'   name='".$id."'".$valid.$maxlen." cols=30  id='".$id."' class='dataInput'".$validate.">".$val."</textarea>
				</td>
				<td width='20'>
				<div id='".$id."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
				</td></tr></table>   
			</td>
		</tr>");
}


function form_generate_input_text($title,$tip,$id,$opt,$val,$mlen,$re, $validation=null) {

	if ($val!=null)
		$value=" value='".$val."' valid='ok'";
	else 
		$value = "";

	$validate=" opt='".$opt."' oninput='validate_input(\"".$id."\", \"".$id."_ok\",".($re?"\"".$re."\"":"null").",".$validation.")'";

	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>");
	if (!is_null($tip))
		print("	<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div> ");
	print("			
			</td>
			<td class='dataRecord' width='250'>
				<table style='width:100%'><tr><td>
				<input type='text' name='".$id."'".$value." id='".$id."' maxlength='".$mlen."' class='dataInput'".$validate.">
				</td>
				<td width='20'>
				<div id='".$id."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
				</td></tr></table>
			</td>
		</tr>");
}

function form_generate_checklist($title, $tip, $id, $mlen, $selected, $vals, $texts=null) { 
	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>");
	if (!is_null($tip))
		print("	<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div> ");
	print("	
			</td>
			<td width='250' >
				<table style='width:100%' class='container'><tr><td>");
	for($i = 0; $i < count($vals); ++$i){
		print("
				<input type='checkbox' name='".$id."[]' value='".$vals[$i]."' id='".$id.$vals[$i]."' ".((in_array($vals[$i], $selected))?"checked":"").">
				<label for=".$id.$vals[$i]." class='dataRecord'>".($texts[$i]?$texts[$i]:$vals[$i])."</label><br>
		");
	}
	print("
				</td>
				<td width='20'>
				<div id='".$id."_ok'></div>
				</td></tr></table>
			</td>
		</tr>");
}

function form_generate_input_checkbox($title,$tip,$id,$val,$checked,$hooks="") {

	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>");
	if (!is_null($tip))
		print("	<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div> ");
	print("
			</td>
			<td class='dataRecord' width='250'>
				<table style='width:100%'><tr><td>
				<input type='checkbox' name='".$id."' value='".$val."' id='".$id."' class='dataInput' ".(($checked==1)?"checked":"")." ".$hooks.">
				</td>
				<td width='20'>
				<div id='".$id."_ok'></div>
				</td></tr></table>
			</td>
		</tr>");
}

function form_generate_passwords($title,$val,$confirm_val,$minimum=6,$tip=null,$opt='y') {

	if ($val!=null)
		$value=" value='".$val."' valid='ok'";
	else
		$value = "";
	if ($confirm_val!=null)
		$confirm_value=" value='".$val."' valid='ok'";
	else 
		$confirm_value = "";

	if (!$tip) {
		$tip = "Password";
	}

	print("
		<tr>
			<td class='dataRecord'>
				<b>Password</b>
				<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>Enter ".$tip."<br> (minimum ".$minimum. " characters)</span>
				</div>
			</td>
			<td class='dataRecord' width='250'>
				<table style='width:100%'><tr><td>
				<input type='password' name='".$title."'".$value." id='".$title."' class='dataInput' autocomplete=\"off\" opt='".$opt.
				"' oninput='validate_input(\"".$title."\", \"".$title."_ok\",\".{".$minimum."}.*\")'>
				</td>
				<td width='20'>
				<div id='".$title."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
				</td></tr></table>
			</td>
		</tr>");
	print("
		<tr>
			<td class='dataRecord'>
				<b>Confirm Password</b>
				<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>Confirm ".$tip."<br></span>
				</div>
			</td>
			<td class='dataRecord' width='250'>
				<table><tr><td>
				<input type='password' name='confirm_".$title."'".$confirm_value." id='confirm_".$title."' class='dataInput' autocomplete=\"off\" opt='".$opt.
				"' oninput='validate_password(\"confirm_".$title."\", \"confirm_".$title."_ok\",\"".$title."\")'>
				</td>
				<td width='20'>
				<div id='confirm_".$title."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
				</td></tr></table>
			</td>
		</tr>");
}

function form_generate_select($title,$tip,$id,$mlen,$val,$vals,$texts=null,$is_optional=false) {
	print("
		<tr>
			<td class='dataRecord'>
				<b>".$title."</b>");
	if (!is_null($tip))
		print("	<div class='tooltip'><sup>?</sup>
				<span class='tooltiptext'>".$tip."</span>
				</div> ");
	print("
			</td>
			<td class='dataRecord' width='250'>
				<table style='width:100%'><tr><td>
				<select name='".$id."' id='".$id."' style='width: ".$mlen."px;' class='dataSelect'>");
	if ($is_optional) {
		print("                                 <option value=''".(($val=="")?" selected":"").">Empty ...</option>");
	}
	for($i = 0; $i < count($vals); ++$i){
		print("
					<option value='".$vals[$i]."'".(($val==$vals[$i])?" selected":"").">".($texts[$i]?$texts[$i]:$vals[$i])."</option>");
	}
	print("
				</select>
				</td>
				<td width='20'>
				<div id='".$id."_ok'></div>
				</td></tr></table>
			</td>
		</tr>");
}


function form_generate_select_refresh($title,$tip,$id,$mlen,$val,$vals,$texts=null) {
	if (!$_POST['selected_val']) $selected = $val;
	else $selected = $_POST['selected_val'];
	$refresh_link = $_SERVER['REQUEST_URI'];
	print ('<form action="'.$refresh_link.'" method="post" id="value_select" name="value_select" >');
	print ('<input type="hidden" name="selected_val" class="formInput" method="post" value="">');
	echo ('<select name="values_list" onChange=console.log()>');
	foreach ( $vals as $value ) {
	  echo '<option value="'.$value.'"' ;
	  if ($_POST['selected_val']==$value) echo ' selected';
	  echo '>'.$value.'</option>';
	}
	echo ('</select></form>');
}

function get_combo_options($combo)
{
	require_once("../../../../config/db.inc.php");
	require_once("../../../../config/tools/".get_tool_path($_SESSION['current_tool'])."/db.inc.php");
	require("lib/db_connect.php");

	$options = array();

	if ( isset($combo['combo_table']) && $combo['combo_table']!="" ){
		if (!isset($combo['combo_display_col']) || $combo['combo_display_col']=="")
			$display_col = $combo['combo_value_col'];
		else
			$display_col = $combo['combo_display_col'];

		if (!isset($combo['combo_hook_col']) || $combo['combo_hook_col']=="")
			$hook_col = NULL;
		else
			$hook_col = $combo['combo_hook_col'];

		$sql="select ".$combo['combo_value_col'].", ".$display_col.(($hook_col==NULL)?"":(", ".$hook_col))." from ".$combo['combo_table'];
		$stm = $link->query($sql);
		if($stm === false) {
			die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
		}
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $k=>$v) {
			$options[ $v[$combo['combo_value_col']] ]['display'] = $v[$display_col] ;
			if ($hook_col!=NULL)
				$options[ $v[$combo['combo_value_col']] ]['hook'] = $v[$hook_col] ;
		}

	} else if (isset($combo['combo_default_values']) && $combo['combo_default_values']!=NULL) {
		foreach ($combo['combo_default_values'] as $k=>$v) {
			$options[ $k ]['display'] = $v ;
			if (isset($combo["combo_default_hooks"]) && $combo["combo_default_hooks"]!=NULL)
				$options[ $k ]['hook'] = $combo["combo_default_hooks"][$k] ;
		}

	}

	return $options;
}

// Helpers to build complet validation regexp

# FreeSWITCH url (fs://[username]:password@host[:port])
$re_fs_url ="(fs://[a-zA-Z0-9]*:[^@]+@[^:]+(:[0-9]+)?)";

# SIP URI
$re_sip_uri = "sip(s)?:([^@]+@)?[^:]+(:[0-9]+)?";

$re_ip = "([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})";

$re_socket = "^([a-zA-Z]+:)?([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})(:[0-9]+)?$";

?>
