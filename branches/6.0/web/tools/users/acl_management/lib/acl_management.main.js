<script language="JavaScript">
  
function confirmDelete(id)
{
 var agree=confirm("Are you sure you want to delete this ACL?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteACL()
{
 var agree=confirm("Are you sure you want to delete this ACL?");
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
	location = "acl_management.php?action=add";
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

function Form_Validator(){
		
		url = "lib/acl_management.add.validate.php?username="+document.getElementById('addnewacl').username.value+"&domain="+document.getElementById('addnewacl').domain.value+"&acl_grp="+document.getElementById('addnewacl').acl_grp.value;
		
		var http = getHTTPObject();
		
		http.open("GET", url, false);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
		result = http.responseText;
		
		if (result == "username") {
			if (document.getElementById('addnewacl').username.value == ""){
				alert("Please enter a value for the \"username\" field.");
				document.getElementById('addnewacl').username.focus();
				return false;
			}
			else {
				alert("This \"username\" doesn't exist.");
				document.getElementById('addnewacl').username.focus();
				return false;
			}
		}
		
		if (result == "domain") {
			if (document.getElementById('addnewacl').domain.value == ""){
				alert("Please enter a value for the \"domain\" field.");
				document.getElementById('addnewacl').domain.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"domain\" field.");
				document.getElementById('addnewacl').domain.focus();
				return false;
			}
		}
		
		if (result == "group") {
			if (document.getElementById('addnewacl').acl_grp.value == "ANY"){
				alert("Please choose a value for the \"group\" field.");
				document.getElementById('addnewacl').acl_grp.focus();
				return false;
			}
			else {
				alert("Please enter another value for the \"group\" field.");
				document.getElementById('addnewacl').acl_grp.focus();
				return false;
			}


		}
		
		

		
	return true;
	}


</script>
