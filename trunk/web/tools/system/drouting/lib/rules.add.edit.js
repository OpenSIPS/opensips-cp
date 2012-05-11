<script language="JavaScript">
//
// $Id: rules.add.edit.js 30 2009-04-13 09:43:17Z iulia_bublea $
//

function clearObject(object_name)
{
 var list=document.getElementById(object_name).value;
 if ((list.charAt(list.length-1)==";") || (list.charAt(list.length-1)==","))
 {
  document.getElementById(object_name).value=list.substring(0,list.length-1);
 }
 else
 {
  var list_modified=list.replace(/;/g,",");
  var list_array=list_modified.split(",");
  var last=list_array[list_array.length-1].length;
  list=list.substring(0,list.length-last);
  document.getElementById(object_name).value=list;
 }
}

function addElementToObject(object_name)
{
if (document.getElementById(object_name).value=="") document.getElementById(object_name).value=document.getElementById(object_name+"_value").value;
  else {
        var values=document.getElementById(object_name).value;
        values_modified=values.replace(/;/g,",");
        var value_array=values_modified.split(",");
        var new_value=document.getElementById(object_name+"_value").value;
        var index=0;
        while (index<value_array.length)
         if (value_array[index]==new_value && object_name!="gwlist" ) {
                                             if (object_name=="groupid") alert("Error: Duplicate Group ID value: '"+new_value+"'");
                                             //if (object_name=="gwlist") alert("Error: Duplicate Gateway Address value: '#"+new_value+"'");
                                             return false;
                                            }
          else index+=1;
        if ((values.charAt(values.length-1)==";") || (values.charAt(values.length-1)==",")) document.getElementById(object_name).value+=document.getElementById(object_name+"_value").value;
         else document.getElementById(object_name).value+=","+document.getElementById(object_name+"_value").value;
       }
}

function is_pos_int(value){
    if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
        if (value >= 0)
            return true;
        else
            return false;
    }
    else {
        return false;
    }
}

function addGWElementToObject(object_name,weight){
    var new_weight = document.getElementById(weight).value;
    if (new_weight!='')
        if (!is_pos_int(new_weight)){
            alert("The value of a gateway's weight has to be a positive integer");
            return false;
        }


    if (document.getElementById(object_name).value==""){
        if (new_weight!='')
                document.getElementById(object_name).value += document.getElementById(object_name+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value += document.getElementById(object_name+"_value").value;
    }
    else {
        var values=document.getElementById(object_name).value;
        var new_value=document.getElementById(object_name+"_value").value;

        if (values.charAt(values.length-1)==",") {
            if (new_weight!='')
                document.getElementById(object_name).value += document.getElementById(object_name+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value += document.getElementById(object_name+"_value").value;
        }
        else{
            if (new_weight!='')
                document.getElementById(object_name).value+=","+document.getElementById(object_name+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value+=","+document.getElementById(object_name+"_value").value;
        }
    }
}

function addCarElementToObject(object_name,weight){
    var new_weight = document.getElementById(weight).value;
	var carrier_list = 'carrierlist';
    if (new_weight!='')
        if (!is_pos_int(new_weight)){
            alert("The value of a carrier's weight has to be a positive integer");
            return false;
        }



    if (document.getElementById(object_name).value==""){
        if (new_weight!='')
                document.getElementById(object_name).value += '#'+document.getElementById(carrier_list+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value += '#'+document.getElementById(carrier_list+"_value").value;
    }
    else {
        var values=document.getElementById(object_name).value;

        if (values.charAt(values.length-1)==",") {
            if (new_weight!='')
                document.getElementById(object_name).value += '#'+document.getElementById(carrier_list+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value += '#'+document.getElementById(carrier_list+"_value").value;
        }
        else{
            if (new_weight!='')
                document.getElementById(object_name).value+=","+'#'+document.getElementById(carrier_list+"_value").value+'='+new_weight;
            else
                document.getElementById(object_name).value+=","+'#'+document.getElementById(carrier_list+"_value").value;
        }
    }
}


function endGroupGwList(object_name)
{
 var values=document.getElementById(object_name).value;
 if ((values!="") && (values.charAt(values.length-1)!=";")) document.getElementById(object_name).value+=";";
}


function frequencyChange()
{
 var object_name="frequency";
 document.getElementById("div_daily").style.display="none";
 document.getElementById("div_weekly").style.display="none";
 document.getElementById("div_monthly").style.display="none";
 document.getElementById("div_yearly").style.display="none";
 if (document.getElementById(object_name).value=="daily") document.getElementById("div_daily").style.display="block";
 if (document.getElementById(object_name).value=="weekly") document.getElementById("div_weekly").style.display="block";
 if (document.getElementById(object_name).value=="monthly") document.getElementById("div_monthly").style.display="block";
 if (document.getElementById(object_name).value=="yearly") document.getElementById("div_yearly").style.display="block";
}

function optionChange(object_name)
{
 var div_object="div_"+object_name;
 if (document.getElementById(object_name).value==1) document.getElementById(div_object).style.display="block";
  else document.getElementById(div_object).style.display="none";
}

function addElement(object_name)
{
 if (document.getElementById(object_name).value=="") document.getElementById(object_name).value=document.getElementById(object_name+"_value").value;
}

function optionClick(object_name) 
{
var selectedItem = document.getElementById(object_name).value;	
var div_object="div_"+object_name;	
 if ( selectedItem == "gw_list" ) {
        document.getElementById("div_gws").style.display='none';
        document.getElementById("div_lists").style.display='none';
 } else if ( selectedItem == "lists" ) {
        document.getElementById("div_gw_list").style.display='block';
        document.getElementById("div_lists").style.display='block';
	document.getElementById("div_gws").style.display='none';
 } else if ( selectedItem == "gws" ) {
        document.getElementById("div_gw_list").style.display='block';
        document.getElementById('div_gws').style.display='block';
        document.getElementById('div_lists').style.display='none';
 }	
}
function clickOpt(object_name) {
var selectedItem = document.getElementById(object_name).value;
alert("ddd"+selectedItem);
var div_object="div_"+object_name;
 if ( selectedItem == "lists" ) {
        document.getElementById("div_gw_list").style.display='block';
        document.getElementById("div_lists").style.display='block';
        document.getElementById("div_gws").style.display='none';
 } else if ( selectedItem == "gws" ) {
        document.getElementById("div_gw_list").style.display='block';
        document.getElementById('div_gws').style.display='block';
        document.getElementById('div_lists').style.display='none';
 }
	

}

</script>
