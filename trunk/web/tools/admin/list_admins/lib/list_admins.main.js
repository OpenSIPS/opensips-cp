<script language="JavaScript">
//
// $Id: list_admins.main.js 28 2009-04-01 15:27:03Z iulia_bublea $
//
  
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
