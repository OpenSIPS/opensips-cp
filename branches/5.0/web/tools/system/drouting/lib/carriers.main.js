<script language="JavaScript">
//
// $Id: lists.main.js 30 2009-04-13 09:43:17Z iulia_bublea $
//
  
function confirmDelete(carrierid)
{
 var agree=confirm("Are you sure you want to delete the Carrier #"+carrierid+" ?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteSearch()
{
 var agree=confirm("Are you sure you want to delete all Mathcing Carriers ?");
 if (agree)	return true;
  else return false;
}

function confirmEnable(carrierid)
{
    var agree=confirm("Are you sure you want to enable Carrier #"+carrierid+" ?");

    if (agree)
        return true;
    else
        return false;
}

function confirmDisable(carrierid)
{
    var agree=confirm("Are you sure you want to disable Carrier #"+carrierid+" ?");

    if (agree)
        return true;
    else
        return false;
}

</script>
