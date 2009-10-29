<script language="JavaScript">
//
// $Id$
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