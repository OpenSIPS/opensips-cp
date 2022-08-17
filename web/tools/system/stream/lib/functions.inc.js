<script language="JavaScript">
  
function closeTracing(arg) {
  const Http = new XMLHttpRequest();
  const url = 'close.php?id=' + arg;
  Http.open("GET", url);
  Http.setRequestHeader('Content-type', 'application/json');
  Http.onreadystatechange =(e) => {
    //console.log(Http.responseText);
  } 
}
function handleHttpResponse(http) {
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

</script>