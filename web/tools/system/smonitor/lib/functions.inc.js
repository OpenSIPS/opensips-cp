<script>
function openStatOverlay(description, className, tool, input){
    url = "statistics.details.php?class="+className+"&description="+description+"&tool="+tool+"&input="+input;
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
    document.getElementById('custom_stat').innerHTML = result;
    centerMe('custom_stat')
    document.getElementById('overlay').onclick = function () {closeStatOverlay();};
    document.getElementById('custom_stat').style.display = 'block';
    window.location.hash = '#tab1';
    location.hash = "tab1";
  
  }
  
  function openImportOverlay(description){
    url = "statistics.import_details.php?description="+description;
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
    document.getElementById('custom_stat').innerHTML = result;
    centerMe('custom_stat')
    document.getElementById('overlay').onclick = function () {closeImportOverlay();};
    document.getElementById('custom_stat').style.display = 'block';
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
      document.getElementById('custom_stat').style.display = 'none';
      document.getElementById('custom_stat').innerHTML = '';
      location.hash = "";
    }
  
  function closeImportOverlay() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('custom_stat').style.display = 'none';
      document.getElementById('custom_stat').innerHTML = '';
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