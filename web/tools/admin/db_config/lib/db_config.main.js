<script language="JavaScript">
  


function confirmDelete()
{
 var agree=confirm("Are you sure you want to delete this entry?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteUser()
{
 var agree=confirm("Are you sure you want to delete this Admin?");
 if (agree)	return true;
  else return false;
}


function toggle(chkbox, group) {   
    var visSetting = (chkbox.checked) ? "visible" : "hidden";
    document.getElementById(group).style.visibility = visSetting;
}

function openStatOverlay(host, port, user, name, pass, id){
    url = "db_config.details.php?host="+host+"&port="+port+"&user="+user+"&name="+name+"&pass="+pass+"&db_id="+id;
    var http = getHTTPObject();
    http.open("GET", url, false);
    http.onreadystatechange = handleHttpResponse(http);
    http.send(null);
    result = http.responseText;
    
    var body = document.body,
      html = document.documentElement;
  
    var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
    var width = Math.max( body.scrollWidth, body.offsetWidth, html.clientWidth, html.scrollwidth, html.offsetwidth );
  
  
    document.getElementById('overlay').style.height = height;
    document.getElementById('overlay').style.width = width;
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('db_config').innerHTML = result;
    centerMe('db_config')
    document.getElementById('overlay').onclick = function () {closeStatOverlay();};
    document.getElementById('db_config').style.display = 'block';
    window.location.hash = '#tab1';
    location.hash = "tab1";
  
  }

  function centerMe(element) {
    //pass element name to be centered on screen
      var pWidth = window.innerWidth;
      var pTop =  window.scrollTop;
      var eWidth = document.getElementById(element).style.width
      var height = document.getElementById(element).style.height
      document.getElementById(element).style.top = '150px';
      //$(element).css('top',pTop+100+'px')
      document.getElementById(element).style.left = parseInt((pWidth / 2) - 170) + 'px';
    }
    
    
    
    function closeStatOverlay() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('db_config').style.display = 'none';
      document.getElementById('db_config').innerHTML = '';
      location.hash = "";
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
