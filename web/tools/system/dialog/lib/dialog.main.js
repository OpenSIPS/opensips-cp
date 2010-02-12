<script language="JavaScript">
//
// $Id: dialog.main.js 28 2009-04-01 15:27:03Z iulia_bublea $
//
  
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
