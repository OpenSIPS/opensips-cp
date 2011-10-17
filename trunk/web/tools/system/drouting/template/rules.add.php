<!--
 *
 * $Id$
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
 *
 -->

<form action="<?=$page_name?>?action=add_verify" method="post">
<table width="465" cellspacing="2" cellpadding="2" border="0">
 <tr align="center">
  <td colspan="2" class="dataTitle">Add new Rule</td>
 </tr>
<?php
 if (isset($form_error)) {
                          echo(' <tr align="center">');
                          echo('  <td colspan="2" class="dataRecord"><div class="formError">'.$form_error.'</div></td>');
                          echo(' </tr>');
                         }
?>
 <tr>
  <td class="dataRecord"><b>Group ID:</b></td>
  <td class="dataRecord">
   <input type="text" name="groupid" id="groupid" value="<?=$groupid?>" maxlength="64" readonly class="dataInput">
   <input type="button" name="clear_groupid" value="Clear" class="formButton" onclick="clearObject('groupid')"><br>
   <input type="button" name="add_groupid" value="Add" class="formButton" onclick="addElementToObject('groupid')"><?=print_groupids()?>
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Prefix:</b></td>
  <td class="dataRecord"><input type="text" name="prefix" value="<?=$prefix?>" maxlength="64" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Time Recurrence:</b><br><center><img src="images/info.gif" onMouseOver="this.style.cursor='pointer'" onClick="window.open('info.html','info','width=500,height=400')"></center></td>
  <td class="dataRecord">
  
   <select name="time_recurrence" class="dataSelect" id="time_recurrence" onChange="optionChange('time_recurrence')">
    <option value="0">Time Recurrence Disabled</option>
    <option value="1">Time Recurrence Enabled</option>
   </select>
  
  <div id="div_time_recurrence" style="display:none">
  
   <hr>
   <b>&nbsp;&middot;&nbsp;Start of interval:</b><br>
   <?=print_date_time("dtstart")?><br><br>
   
   <b>&nbsp;&middot;&nbsp;Duration of interval:</b>
   <select name="duration" class="dataSelect" id="duration" onChange="optionChange('duration')">
    <option value="0">Forever</option>
    <option value="1">Other</option>
   </select><br>
   
   <div id="div_duration" style="display:none">
    <?=print_interval("duration")?><br>
    <br><b>&nbsp;&middot;&nbsp;Frequency:</b>
    <select name="frequency" class="dataSelect" id="frequency" onChange="frequencyChange()">
     <option value="daily">Daily</option>
     <option value="weekly">Weekly</option>
     <option value="monthly">Monthly</option>
     <option value="yearly">Yearly</option>
    </select><br>
    
    <div id="div_daily" style="display:block">
     <table width="99%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">Every : <input type="text" name="daily_interval" value="1" class="dataInputCustom" size="2"> Day(s)</td>
      </tr>
     </table>
    </div>
    
    <div id="div_weekly" style="display:none">
     <table width="99%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">Every : <input type="text" name="weekly_interval" value="1" class="dataInputCustom" size="2"> Week(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">By Day :</td>
       <td><input type="text" name="weekly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
    <div id="div_monthly" style="display:none">
     <table width="99%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">Every : <input type="text" name="monthly_interval" value="1" class="dataInputCustom" size="2"> Month(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">By Day :</td>
       <td><input type="text" name="monthly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">By Month Day :</td>
       <td><input type="text" name="monthly_bymonthday" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
    <div id="div_yearly" style="display:none">
     <table width="99%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       <td class="timeRecord" colspan="2">Every : <input type="text" name="yearly_interval" value="1" class="dataInputCustom" size="2"> Year(s)</td>
      </tr>
      <tr>
       <td class="timeRecord">By Day :</td>
       <td><input type="text" name="yearly_byday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">By Month Day :</td>
       <td><input type="text" name="yearly_bymonthday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">By Year Day :</td>
       <td><input type="text" name="yearly_byyearday" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">By Week No :</td>
       <td><input type="text" name="yearly_byweekno" value="" class="dataInputCustom" size="35"></td>
      </tr>
      <tr>
       <td class="timeRecord">By Month :</td>
       <td><input type="text" name="yearly_bymonth" value="" class="dataInputCustom" size="35"></td>
      </tr>
     </table>
    </div>
    
   </div>
   <br><b>&nbsp;&middot;&nbsp;Bound of Recurrence:</b>
   <select name="bound" class="dataSelect" id="bound" onChange="optionChange('bound')">
    <option value="0">None</option>
    <option value="1">Other</option>
   </select><br>
   <div id="div_bound" style="display:none"><?=print_date_time("until")?></div>
   
  </div>
  
  </td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Priority:</b></td>
  <td class="dataRecord"><input type="text" name="priority" value="<?=$priority?>" maxlength="11" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Route ID:</b></td>
  <td class="dataRecord"><input type="text" name="routeid" value="" maxlength="11" class="dataInput"></td>
 </tr>
 <tr>
  <td class="dataRecord"><b>Gateway List:</b></td>
  <td class="dataRecord">
   <select name="gw_list" class="dataSelect" id="gw_list" onChange="optionClick('gw_list')">
    <option value="gw_list">--Select--</option>
    <option value="lists">Use Gateway List</option>
    <option value="gws">Use Gateways</option>
   </select>

   <div id='div_gw_list' style="display:none">

    <div id="div_lists" style="display:none">
     <input type="text" name="lists" id="lists" value="<?php echo $lists;?>" maxlength="255" readonly class="dataInput">
     <input type="button" name="clear_lists" value="Clear" class="formButton" onclick="clearObject('lists')"><br>
     <input type="button" name="add_lists" value="Set" class="formButton" onclick="addElement('lists')"><?=print_lists()?>&nbsp;

    </div>

   <div id="div_gws" style="display:none">
    <input type="text" name="gwlist" id="gwlist" value="<?php echo $gwlist?>" maxlength="255" readonly class="dataInput">
    <input type="button" name="clear_gwlist" value="Clear" class="formButton" onclick="clearObject('gwlist')"><br>
    <input type="button" name="add_gwlist" value="Add" class="formButton" onclick="addElementToObject('gwlist')"><?=print_gwlist()?>&nbsp;|&nbsp;
    <input type="button" name="end_group_gwlist" value="End Group" class="formButton" onclick="endGroupGwList('gwlist')"><br>
   </div>
  </div>
 </td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Attributes:</b></td>
  <td class="dataRecord"><input type="text" name="attrs" value="<?=$attrs?>" maxlength="12828 class="dataInput"></td>
 </tr>

 <tr>
  <td class="dataRecord"><b>Description:</b></td>
  <td class="dataRecord"><input type="text" name="description" value="<?=$description?>" maxlength="128" class="dataInput"></td>
 </tr>
 <tr>
  <td colspan="2" class="dataRecord" align="center"><input type="submit" name="add" value="Add" class="formButton"></td>
 </tr>
 <tr height="10">
  <td colspan="2" class="dataTitle"><img src="images/spacer.gif" width="5" height="5"></td>
 </tr>
</table>
</form>
<br>
<?=$back_link?>
