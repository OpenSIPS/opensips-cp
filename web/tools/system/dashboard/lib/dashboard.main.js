<script language="JavaScript">
  
function confirmDelete(name)
{
 var agree=confirm("Are you sure you want to delete this "+name+"?");
 if (agree)	return true;
  else return false;
}

function toggle(chkbox, group) {   
    var visSetting = (chkbox.checked) ? "visible" : "hidden";
    document.getElementById(group).style.visibility = visSetting;
}

</script>
