<script language="JavaScript">
//
// $Id: gateways.main.js 40 2009-04-13 14:59:22Z iulia_bublea $
//

function handleHttpResponse() {

        if (http.readyState == 4) {
              if(http.status==200) {
                  ok = true;
                  //return results;
              }

        }

}



function getHTTPObject() {

  var request = false;
   try {
     request = new XMLHttpRequest();
   } catch (trymicrosoft) {
     try {
       request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (othermicrosoft) {
       try {
         request = new ActiveXObject("Microsoft.XMLHTTP");
       } catch (failed) {
         request = false;
       }
     }
   }

   if (!request)
     alert("Error initializing XMLHttpRequest!");


  return request;


}

function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do { curDate = new Date(); }
while(curDate-date < millis);
}


  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete Gateway #"+id+" ?");
 if (agree)	return true;
  else return false;
}

function confirmEnable(gwid)
{
	var agree=confirm("Are you sure you want to enable Gateway #"+gwid+" ?");
	
	if (agree) 
		return true;
	else 
  		return false;
}

function confirmDisable(gwid)
{
    var agree=confirm("Are you sure you want to disable Gateway #"+gwid+" ?");

    if (agree)
        return true;
    else
        return false;
}

</script>
