<script language="JavaScript">
  
function confirmDelete()
{
 var agree=confirm("Are you sure you want to delete this entry?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteUser()
{
 var agree=confirm("Are you sure you want to delete this Admin?");
 if (agree)	return true;
  else return false;
}


function toggle(chkbox, group) {   
    var visSetting = (chkbox.checked) ? "visible" : "hidden";
    document.getElementById(group).style.visibility = visSetting;
}

</script>
