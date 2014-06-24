<script language="JavaScript">
//
// $Id: list_users.main.js 28 2009-04-01 15:27:03Z iulia_bublea $
//
  
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


</script>
