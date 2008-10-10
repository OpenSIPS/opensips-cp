<script language="JavaScript">
//
// $Id: rules.main.js,v 1.1.1.1 2006-08-30 10:43:08 bogdan Exp $
//
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete Rule #"+id+" ?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteSearch()
{
 var agree=confirm("Are you sure you want to delete all Matching Rules ?");
 if (agree)	return true;
  else return false;
}

</script>