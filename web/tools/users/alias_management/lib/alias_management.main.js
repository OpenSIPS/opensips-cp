<script language="JavaScript">
  
function setReadonly( selectElementId ){
	var selectElement = document.getElementById(selectElementId);
	if (selectElement){		
		var parent = selectElement.parentElement;
		var textValue = selectElement.options[selectElement.options.selectedIndex].innerText;
		if (!parent){
			parent=selectElement.parentNode;
			textValue = selectElement.options[selectElement.options.selectedIndex].text;
		}
		var input = document.createElement("input");
		input.setAttribute("id",selectElement.id);
		input.setAttribute("type","text");
		input.setAttribute("value",textValue);
		input.style.background="#cccccc";
		input.readOnly = true;
		parent.appendChild(input);
	}
	selectElement.style.display="none";
}

function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete this Alias?");
 if (agree)	return true;
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
