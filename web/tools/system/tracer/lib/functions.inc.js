<script language="JavaScript">

function confirmStart()
{
	
	var caller = document.getElementById('caller_id').value;
	var callee = document.getElementById('callee_id').value;
	var ip = document.getElementById('ip_id').value;
	if (!caller && !callee && !ip) {
		var agree=confirm("Are you sure you want to start tracing without filters?");
		if (agree)	return true;
			else return false;
	} else return true;
}

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