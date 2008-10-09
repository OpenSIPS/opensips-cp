<script language="JavaScript">
//
// $Id: pdt.main.js,v 1.1.1.1 2006-08-30 10:43:11 bogdan Exp $
//
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete '"+id+"' ?");
 if (agree)	return true;
  else return false;
}

</script>