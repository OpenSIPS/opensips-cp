<script language="JavaScript">
//
// $Id: alias_management.main.js 40 2009-04-13 14:59:22Z iulia_bublea $
//
  
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

function confirmDeleteUser()
{
 var agree=confirm("Are you sure you want to delete this Alias?");
 if (agree)     return true;
  else return false;
}

function checkAliasFormat (){
	var agree=confirm("Are you sure you want to continue?");
 if (agree)	return true;
  else return false;
}

function goAdd(){

	//document.getElementById('refreshform');
	//document.forms['refreshform'].submit();
	location = "alias_management.php?action=add";
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

function Form_Validator(alias_format){
		
		url = "lib/alias_management.add.validate.php?username="+document.getElementById('addnewalias').username.value+"&domain="+document.getElementById('addnewalias').domain.value+"&alias_username="+document.getElementById('addnewalias').alias_username.value+"&alias_domain="+document.getElementById('addnewalias').alias_domain.value+"&alias_type="+document.getElementById('addnewalias').alias_type.value;
		
		var http = getHTTPObject();
		
		http.open("GET", url, false);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
		result = http.responseText;
		
		if (result == "username") {
			if (document.getElementById('addnewalias').username.value == ""){
				alert("Please enter a value for the \"username\" field.");
				document.getElementById('addnewalias').username.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"username\" field.");
				document.getElementById('addnewalias').username.focus();
				return false;
			}
		}
		
		if (result == "domain") {
			if (document.getElementById('addnewalias').domain.value == ""){
				alert("Please enter a value for the \"domain\" field.");
				document.getElementById('addnewalias').domain.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"domain\" field.");
				document.getElementById('addnewalias').domain.focus();
				return false;
			}
		}
		
		if (result == "alias_domain") {
			if (document.getElementById('addnewalias').alias_domain.value == ""){
				alert("Please enter a value for the \"alias_domain\" field.");
				document.getElementById('addnewalias').alias_domain.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"alias_domain\" field.");
				document.getElementById('addnewalias').alias_domain.focus();
				return false;
			}
		}
		
		if (result == "alias_type") {
			if (document.getElementById('addnewalias').alias_type.value == ""){
				alert("Please enter a value for the \"alias_type\" field.");
				document.getElementById('addnewalias').alias_type.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"alias_type\" field.");
				document.getElementById('addnewalias').alias_type.focus();
				return false;
			}
		}
		
		if (result == "alias_username_empty") {
				alert("Please enter a value for the Alias Username field.");
				document.getElementById('addnewalias').alias_username.focus();
				return false;
		}
			
		if (result == "alias_username_format") {
			var agree=confirm("The Alias Username does not match the alias username format. Do you want to continue?");
			if (agree)	return true;
			else {
				document.getElementById('addnewalias').alias_username.focus();
				return false;
			}
		}
		
		if (result == "alias_username_exists") {
			var agree=confirm("The Alias Username already exists. Do you want to continue?");
			if (agree)	return true;
			else {
				document.getElementById('addnewalias').alias_username.focus();
				return false;
			}
		}
		
		if (result == "alias_username_format_exists") {
			var agree=confirm("The Alias Username does not match the alias username format. Do you want to continue?");
			if (agree)	{
				var agree2=confirm("The Alias Username already exists. Do you want to continue?");
				if (agree2)
					return true;
				else {
					document.getElementById('addnewalias').alias_username.focus();
					return false;
				}
			}
			else {
				document.getElementById('addnewalias').alias_username.focus();
				return false;
			}
		}

		
	return true;
	}


</script>
