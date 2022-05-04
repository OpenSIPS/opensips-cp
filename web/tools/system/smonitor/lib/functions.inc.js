<script>
function show_statistic(description){
    
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
    document.getElementById('dialog').innerHTML = result;
    centerMe('dialog')
    document.getElementById('overlay').onclick = function () {closeDialog();};
    document.getElementById('dialog').style.display = 'block';
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
    
    
    
    function closeDialog() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('dialog').style.display = 'none';
      document.getElementById('dialog').innerHTML = '';
      location.hash = "";
    }

</script>