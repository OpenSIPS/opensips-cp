<script language="JavaScript">
//
// $Id: domains.main.js 40 2009-04-13 14:59:22Z iulia_bublea $
//
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete Domain '"+id+"' ?");
 if (agree)	return true;
  else return false;
}

</script>