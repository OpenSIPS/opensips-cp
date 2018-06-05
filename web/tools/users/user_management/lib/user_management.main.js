<script language="JavaScript">
  
function confirmDelete()
{
 var agree=confirm("Are you sure you want to delete this entry?");
 if (agree)	return true;
  else return false;
}

function confirmDeleteUser()
{
 var agree=confirm("Are you sure you want to delete this Subscriber?");
 if (agree)	return true;
  else return false;
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

function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do { curDate = new Date(); }
while(curDate-date < millis);
}


function showContacts(username,domain,w,h) {

// Creates a relatively UNIQUE window id so that subsequent
// links don't load in the same window
var day =new Date();
var id = day.getTime();
var wt = w+50; // make room for scrollbars
var ht = h+125; // scrollbars and close button
var title = 'Contact Info';

if ((screen.height) && (ht > screen.height-150)) 
	ht = screen.height-150;

var params = 'width='+wt+',height='+ht+',scrollbars';

var win = open('contacts.php?username='+username+'&domain='+domain,id,params);


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

function show_contacts(username,domain){
		url = "show_contacts.php?username="+username+"&domain="+domain;
		
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

</script>
