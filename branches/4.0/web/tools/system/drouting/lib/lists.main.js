<script language="JavaScript">
//
// $Id: lists.main.js 30 2009-04-13 09:43:17Z iulia_bublea $
//
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete GW List #"+id+" ?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteSearch()
{
 var agree=confirm("Are you sure you want to delete all Mathcing Lists ?");
 if (agree)	return true;
  else return false;
}

</script>
