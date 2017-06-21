<script language="JavaScript">
  
function confirmDelete()
{
 var agree=confirm("Are you sure you want to terminate the call?");
 if (agree) {

		return true;

	}
  else return false;
}

function confirmDeleteRTPproxy()
{
 var agree=confirm("Are you sure you want to delete this Dialog definition?");
 if (agree)	return true;
  else return false;
}

function refreshDlg(){
	document.getElementById('refreshform');
	document.forms['refreshform'].submit();
}


</script>
