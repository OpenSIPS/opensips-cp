<script language="JavaScript">
//
// $Id: domains.main.js,v 1.1.1.1 2006-08-30 10:43:10 bogdan Exp $
//
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete Domain '"+id+"' ?");
 if (agree)	return true;
  else return false;
}

</script>