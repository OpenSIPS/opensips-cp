<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
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

<form action="<?=$page_name?>?action=add_verify" method="post">
<?php csrfguard_generate(); ?>
<table width="465" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="mainTitle">Add new Rule</td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>

 <tr>
  <td class="dataRecord">Group ID</td>
  <td class="dataRecord">
    <input type="text" name="groupid" id="groupid" value="<?=$groupid?>" maxlength="64" style="width:420px!important" readonly class="dataInput">
    <input type="button" name="clear_groupid" value="Clear Last" class="inlineButton" style="width:90px;" onclick="clearObject('groupid')">
  </td>
 </tr>
 <tr>
  <td/>
  <td class="dataRecord">
   <?=print_groupids()?>
   <input type="button" name="add_groupid" value="Add ID" class="inlineButton" style="width:90px" onclick="addElementToObject('groupid')">
  </td>
 </tr> 
 <tr>
  <td class="dataRecord">Prefix</td>
  <td class="dataRecord"><input type="text" name="prefix" value="<?=$prefix?>"  style="width:230px;" maxlength="64" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord">Time Recurrence<!--<br><center><img src="../../../images/share/info.png" onMouseOver="this.style.cursor='pointer'" onClick="window.open('info.html','info','width=500,height=400')"></center>--></td>
  <td class="dataRecord">
  
   <select name="time_recurrence" class="dataSelect" id="time_recurrence" style="width:230px;"  onChange="optionChange('time_recurrence')">
    <option value="0">Time Recurrence Disabled</option>
    <option value="1">Time Recurrence Enabled</option>
   </select>
  
  <div id="div_time_recurrence" style="display:none">
  
   <hr>
   <b>&nbsp;&middot;&nbsp;Start of interval:</b>
   <?=print_date_time("dtstart")?><br><br>

   <b>&nbsp;&middot;&nbsp;Duration of interval:</b>
   <select name="duration" class="dataSelect" id="duration" style="width:150px!important" onChange="optionChange('duration')">
    <option value="0">Forever</option>
    <option value="1">Other</option>
   </select><br>
   
   <div id="div_duration" style="display:none">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=print_interval("duration")?><br>
    <br><b>&nbsp;&middot;&nbsp;Frequency:</b>
    <select name="frequency" class="dataSelect" style="width:150px!important" id="frequency" onChange="frequencyChange()">
     <option value="daily">Daily</option>
     <option value="weekly">Weekly</option>
     <option value="monthly">Monthly</option>
     <option value="yearly">Yearly</option>
    </select><br>
    
    <div id="div_daily" style="display:block">
     <table style="width:99%!important" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Every : <input type="text" name="daily_interval" value="1" class="dataInputCustom" size="2"> Day(s)</td>
      </tr>
     </table>
    </div>
    
    <div id="div_weekly" style="display:none">
     <table style="width:99%!important" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Every : <input type="text" name="weekly_interval" value="1" class="dataInputCustom" size="2"> Week(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Day :</td>
       <td><input type="text" name="weekly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
    <div id="div_monthly" style="display:none">
     <table style="width:99%!important" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Every : <input type="text" name="monthly_interval" value="1" class="dataInputCustom" size="2"> Month(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Day :</td>
       <td><input type="text" name="monthly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Month Day :</td>
       <td><input type="text" name="monthly_bymonthday" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
    <div id="div_yearly" style="display:none">
     <table style="width:99%!important" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Every : <input type="text" name="yearly_interval" value="1" class="dataInputCustom" size="2"> Year(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Day :</td>
       <td><input type="text" name="yearly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Month Day :</td>
       <td><input type="text" name="yearly_bymonthday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Year Day :</td>
       <td><input type="text" name="yearly_byyearday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Week No :</td>
       <td><input type="text" name="yearly_byweekno" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By Month :</td>
       <td><input type="text" name="yearly_bymonth" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
   </div>
   <br><b>&nbsp;&middot;&nbsp;Bound of Recurrence:</b>
   <select name="bound" class="dataSelect" id="bound" style="width:150px!important" onChange="optionChange('bound')">
    <option value="0">None</option>
    <option value="1">Other</option>
   </select><br>
   <div id="div_bound" style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=print_date_time("until")?></div>
   
  </div>
  
  </td>
 </tr>
 <tr>
  <td class="dataRecord">Priority</td>
  <td class="dataRecord"><input type="text" name="priority" value="<?=$priority?>" style="width:230px;" maxlength="11" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord">Route ID</td>
  <td class="dataRecord"><input type="text" name="routeid" value="" style="width:230px;" maxlength="11" class="dataInput"></td>
 </tr>

 <tr>
  <td  class="dataRecord">Destination's List</td>
   <td class="dataRecord">
      <input type="text"   name="gwlist" id="gwlist" value="<?=$gwlist?>" style="width:393px!important" maxlength="<?=(isset($config->gwlist_size)?$config->gwlist_size:255)?>" readonly class="dataInput">
      <input type="button" name="clear_gwlist" value="Clear Last" class="inlineButton" style="width:120px" onclick="clearObject('gwlist')"><br>
   </td>
  </tr>
 <tr>
  <td/>
  <td class="dataRecord">
   <?=print_gwlist()?>
   <input type="text"   name="gw_weight" id="gw_weight" value="" maxlength="5" class="dataInput" style="width:40!important;">
   <input type="button" name="add_gwlist" value="Add GW" class="inlineButton" style="width:120px;" onclick="addGWElementToObject('gwlist','gw_weight')">
  </td>
 </tr>
 <tr>
  <td/>
  <td class="dataRecord">
   <?=print_carrierlist()?>
   <input type="text"   name="car_weight" id="car_weight" value="" maxlength="5" class="dataInput" style="width:40!important;">
   <input type="button" name="add_carrier" value="Add carrier" class="inlineButton" style="width:120px;" onclick="addCarElementToObject('gwlist','car_weight')">
  </td>
 </tr>

 <tr>
  <td class="dataRecord">List Sorting</td>
  <td class="dataRecord"><select name="list_sort" id="list_sort" style="width:230px;" class="dataSelect"><?php dr_get_options_of_list_sort(NULL)?></select></td>
 </tr>

<?php if (get_settings_value("rules_attributes_mode") != "none") { ?>
 <?php $rules_attributes = get_settings_value("rules_attributes"); ?>
 <tr>
  <td class="dataRecord"><b><?=(isset($rules_attributes["display_name"])?$rules_attributes["display_name"]:"Attributes")?></b></td>
   <?php if (!isset($attrs) || $attrs=="") $attrs=$rules_attributes["add_prefill_value"] ?>
  <td class="dataRecord"><input type="text" name="attrs" value="<?=$attrs?>" style="width:230px;" maxlength="128" class="dataInput">
  </td>
 </tr>
<?php } ?>

 <tr>
  <td class="dataRecord">Description</td>
  <td class="dataRecord"><input type="text" name="description" value="<?=$description?>" style="width:230px;" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2">
	<table cellspacing=20>
	<tr>
	<td class="dataRecord" align="right" width="50%">
	<input type="submit" name="add" value="Add" class="formButton"></td>
	<td class="dataRecord" align="left" width="50%"><?php print_back_input(); ?></td>
	</tr>
	</table>
  </td>
 </tr>
</table>
</form>
