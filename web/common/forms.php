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

  function get_element_selected(id, referid) {
      var elementId = document.getElementById(id);
      var mode = elementId.options[elementId.selectedIndex].value;
      var mode_txt = elementId.options[elementId.selectedIndex].text;
      //alert("Selected Item " + mode + ", Value " + mode_txt);

      //var referid_tooltip = document.getElementById(referid) + "_tooltip";
      var referid_tooltip = referid + "_tooltip";

      // RegEx matching a FQDN
      var re_fqdn;
      re_fqdn = "((?=.{4,253})";
      re_fqdn = re_fqdn + "(((?!-)[a-zA-Z0-9-]{0,62}[a-zA-Z0-9]\.)";
      re_fqdn = re_fqdn + "+[a-zA-Z]{2,63}))";
      //alert(re_fqdn);

      var re_ipv4;
      re_ipv4 = "(";
      re_ipv4 = re_ipv4 + "([0-9]|[1-9][0-9]|1[0-9]{2}|";
      re_ipv4 = re_ipv4 + "2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|";
      re_ipv4 = re_ipv4 + "1[0-9]{2}|2[0-4][0-9]|25[0-5]";
      re_ipv4 = re_ipv4 + ")";
      //alert(re_ipv4);

      // RegEx matching an IPv6 addr
      re_ipv6 = "(";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,7}:|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|";
      re_ipv6 = re_ipv6 + "[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|";
      re_ipv6 = re_ipv6 + ":((:[0-9a-fA-F]{1,4}){1,7}|";
      re_ipv6 = re_ipv6 + ":)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|";
      re_ipv6 = re_ipv6 + "::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|";
      re_ipv6 = re_ipv6 + "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|";
      re_ipv6 = re_ipv6 + "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|";
      re_ipv6 = re_ipv6 + "([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|";
      re_ipv6 = re_ipv6 + "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|";
      re_ipv6 = re_ipv6 + "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])";
      re_ipv6 = re_ipv6 + ")";
      //alert(re_ipv6);

      // RegEx matching a PSTN Number
      re_pstn = "([0-9+]+)";
      //alert(re_pstn);

      // RegEx matching URI's: ipv4addr or ipv6addr or fqdn
      re_uris = "(" + re_ipv4;
      re_uris = re_uris + "|" + re_ipv6;
      re_uris = re_uris + "|" + re_fqdn;
      re_uris = re_uris + ")?";
      //alert(re_uris);

      // RegEx IP's: ipv4addr or ipv6addr
      re_ips = "(" + re_ipv4;
      re_ips = re_ips + "|" + re_ipv6;
      re_ips = re_ips + ")?";
      //alert(re_ips);

      // RegEx SIP URI's: sip or sips : ipv4addr or ipv6addr or fqdn
      re_sip_uris = "sip(s)?:";
      re_sip_uris = re_sip_uris + "(" + re_ipv4;
      re_sip_uris = re_sip_uris + "|" + re_ipv6;
      re_sip_uris = re_sip_uris + "|" + re_fqdn;
      re_sip_uris = re_sip_uris + ")?";
      //alert(re_sip_uris);

      // RegEx Static-Mode
      re_static_mode = "^sip(s)?:";
      re_static_mode = re_static_mode + re_uris;
      re_static_mode = re_static_mode + "(:(5060|5061)?)?;bnc$";
      //alert(re_static_mode);

      // RegEx Registration-Mode
      re_reg_mode = "^sip(s)?:";
      re_reg_mode = re_reg_mode + re_pstn + "@";
      re_reg_mode = re_reg_mode + re_uris + "(:(5060|5061)?)?$";
      //alert(re_reg_mode);

      ret = -1;
      if (mode == "RFC6140") {
	  var tooltip_RFC6140 = "Contact Header associated to the given SIP registrant (eg: sip:'IP-Addr':'port';bnc)";
	  //alert(registrar_tooltip);
	  //document.getElementById(referid).value = "RFC6140";
	  document.getElementById(referid_tooltip).innerText = tooltip_RFC6140;
	  document.getElementById(referid).setAttribute("re", re_static_mode);
	  ret = 1;

      } else if (mode == "RFC3261") {
	  var tooltip_RFC3261 = "Contact Header associated to the given SIP registrant (eg: sip:'PSTN-Nr'@sip-trunk.telekom.de:5060)"
	  //document.getElementById(referid).value = "RFC3261";
	  document.getElementById(referid_tooltip).innerText = tooltip_RFC3261;
	  document.getElementById(referid).setAttribute("re", re_reg_mode);
	  ret = 1;
      }

      return ret;
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
	  } else if (elem[i].type=="submit")
	      button = elem[i];
      }
      if (button!=null) {
	  if ( ret )
	      button.disabled = false;
	  else
	      button.disabled = true;
      }
  }

  function validate_input(field, output, regex){
      val = document.getElementById(field).value;
      if (val=="") {
	  if (document.getElementById(field).getAttribute("opt")=="y")
	      document.getElementById(output).innerHTML = '';
	  else
	      document.getElementById(output).innerHTML = '<img src="../../../images/share/must-icon.png">';
	  document.getElementById(field).setAttribute("valid","ko");
	  ret =-1;
      } else if (regex == null || val.match(new RegExp( regex,"g")) ) {
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

 </script>

 <?php
 // server side
 function form_generate_input_text($title,$tip,$id,$opt,$val,$mlen,$re) {

     if ($val != null)
	 $value = " value='" . $val . "' valid='ok'";
     else
	 $value = "";

     if ($re == null)
	 $validate = "";
     else
	 $validate= " opt='" . $opt . "' oninput='validate_input(\"" . $id . "\", \"" . $id . "_ok\", \"" . $re . "\")'";

     print("
<tr>
    <td class='dataRecord'>
	<b>" . $title . "</b>
	<div class='tooltip'><sup>?</sup>
	    <span class='tooltiptext' id='" . $id . "_tooltip'>" . $tip . "</span>
	</div>
    </td>
    <td class='dataRecord' width='380'>
	<table style='width:100%'>
	    <tr>
		<td>
		    <input type='text' name='" . $id . "'" . $value . " id='" . $id . "'
			maxlength='" . $mlen . "' class='dataInput'" . $validate . ">
		</td>
		<td width='20'>
		    <div id='" . $id . "_ok'>" .
		       ( ($opt == 'y' || $val != null) ? ("") : ("<img src='../../../images/share/must-icon.png'>") ) . "
		    </div>
		</td>
	    </tr>
	</table>
    </td>
</tr>
    ");
 }

 function form_generate_input_registrar($title,$registrar_tooltip,$id,$opt,$val,$mlen,$re) {

     if ($val != null)
	 $value = " value='" . $val . "' valid='ok' re=''";
     else
	 $value = "";

     if ($re == null)
	 $validate = "";
     else
	 $validate = " opt='".$opt."' oninput='validate_input(\"".$id."\", \"".$id."_ok\",\"".$re."\")'";

     print("
<tr>
    <td class='dataRecord'>
	<b>" . $title . "</b>
	<div class='tooltip'><sup>?</sup>
	    <span class='tooltiptext' id='" . $id . "_tooltip'>" . $tip . "</span>
	</div>
    </td>
    <td class='dataRecord' width='380'>
	<table style='width:100%'>
	    <tr>
		<td>
		    <input type='text' name='" . $id . "'" . $value . " id='" . $id . "' maxlength='" . $mlen . "'
			class='dataInput'" . $validate . ">
		</td>
		<td width='20'>
		    <div id='" . $id . "_ok'>" .
			( ($opt == 'y' || $val != null ) ? ("") : ("<img src='../../../images/share/must-icon.png'>") ) ."
		    </div>
		</td>
	    </tr>
	</table>
    </td>
</tr>"
     );
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
	    <input type='password' name='".$title."'".$value." id='".$title."' class='dataInput' opt='".$opt.
	   "' oninput='validate_input(\"".$title."\", \"".$title."_ok\",\".{".$minimum."}.*\")'>
	</td>
	<td width='20'>
	    <div id='".$title."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
	</td></tr></table>
    </td>
</tr>
     ");

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
	    <input type='password' name='confirm_".$title."'".$confirm_value." id='confirm_".$title."' class='dataInput' opt='".$opt.
	   "' oninput='validate_password(\"confirm_".$title."\", \"confirm_".$title."_ok\",\"".$title."\")'>
	</td>
	<td width='20'>
	    <div id='confirm_".$title."_ok'>".(($opt=='y' || $val!=null)?(""):("<img src='../../../images/share/must-icon.png'>"))."</div>
	</td></tr></table>
    </td>
</tr>
    ");
 }

 function form_generate_select($title,$tip,$id,$mlen,$val,$vals,$texts=null,$referid) {

     // content of $ds_form['attribute'] is passed as $val
     // if not 'preset' -> mark first arry element as 'selected'
     $value = array();

     if ( $val == null )
	 $val = $vals[0];

     // debug:
     #print(" <tr><td width='400'> val: '" . $val . "' </td> </tr>" );

     print("
<tr>
    <td class='dataRecord'>
	<b>".$title."</b>
	<div class='tooltip'><sup>?</sup>
	    <span class='tooltiptext'>".$tip."</span>
	</div>
    </td>
    <td class='dataRecord' width='250'>
	<table style='width:100%'>
	    <tr>
		<td>
		    <select name='" . $id ."' id='" . $id . "' style='width: " . $mlen . "px;' class='dataSelect'
			    onChange='get_element_selected(\"".$id."\", \"".$referid."\")'>
			    //onfocus='get_element_selected(\"".$id."\", \"".$referid."\")'>
			"
     );

     for( $i = 0; $i < count($vals); ++$i ) {
	 print("<option value='" . $vals[$i] . "'" .
	       ( ( $val == $vals[$i] ) ? " selected" : "" ) .
	       ">" .
	       ( $texts[$i] ? $texts[$i] : $vals[$i] ) .
	       "</option>"
	 );

	 // debug:
	 //$value[$i] = ( $val == $vals[$i] ) ? ' selected' : ' not selected';
     }
     print("
		    </select>
		</td>
		<td width='20'>
		    <div id='".$id."_ok'></div>
		</td>
	    </tr>
	</table>
    </td>
</tr>
    ");

     // debug:
     //for ( $i = 0; $i < count($vals); ++$i ) {
     // print(" <tr><td width='400'> key[" . $i . "]: '" . $vals[$i] . "value[" . $i . "]: '" . $value[$i] . "' </td> </tr>" );
     //}

     //print(" <tr><td width='400'> Selected-Id:" . $_POST['registrar_mode'] . "' </td> </tr>" );
     //$registrar_mode = $_POST['registrar_mode'];
     #$selected = $_POST['registrar_mode'];
     //print(" <tr><td width='400'> Selected-Id:" . $registrar_mode . "' </td> </tr>" );
     //return $registrar_mode;
 }

 // Helpers to build complet validation regexp

 # FreeSWITCH url (fs://[username]:password@host[:port])
 $re_fs_url ="(fs://[a-zA-Z0-9]*:[^@]+@[^:]+(:[0-9]+)?)";

 # RegEx matching an IPv4 addr
 $re_ipv4 = "(" .
	    "([0-9]|[1-9][0-9]|1[0-9]{2}|" .
	    "2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|" .
	    "1[0-9]{2}|2[0-4][0-9]|25[0-5]" .
	    ")";

 # RegEx matching an IPv6 addr
 $re_ipv6 = "(" .
	    "([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|" .
	    "([0-9a-fA-F]{1,4}:){1,7}:|" .
	    "([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|" .
	    "([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|" .
	    "([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|" .
	    "([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|" .
	    "([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|" .
	    "[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|" .
	    ":((:[0-9a-fA-F]{1,4}){1,7}|" .
	    ":)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|" .
	    "::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|" .
	    "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|" .
	    "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|" .
	    "([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|" .
	    "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|" .
	    "(2[0-4]|1{0,1}[0-9]){0,1}[0-9])" .
	    ")";

 # RegEx matching a FQDN
 $re_fqdn = "((?=.{4,253})" .
	    "(((?!-)[a-zA-Z0-9-]{0,62}[a-zA-Z0-9]\.)" .
	    "+[a-zA-Z]{2,63}))";

 # RegEx matching a PSTN Number
 $re_pstn = "([0-9+]+)";

 # RegEx matching URI's: ipv4addr or ipv6addr or fqdn
 $re_uris = "(" . $re_ipv4 .
	    "|" . $re_ipv6 .
	    "|" . $re_fqdn .
	    ")?";

 # RegEx IP's: ipv4addr or ipv6addr
 $re_ips = "(" . $re_ipv4 .
	   "|" . $re_ipv6 .
	   ")?";

 # RegEx SIP URI's: sip or sips : ipv4addr or ipv6addr or fqdn
 $re_sip_uris = "sip(s)?:" .
		"(" . $re_ipv4 .
		"|" . $re_ipv6 .
		"|" . $re_fqdn .
		")?";

 ?>
