<script language="JavaScript">
  
function confirmDeleteGRP(id)
{
 var agree=confirm("Are you sure you want to delete this Group?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteALL()
{
 var agree=confirm("Are you sure you want to delete ALL the selected Groups?");
 if (agree)     return true;
  else return false;
}

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

</script>
