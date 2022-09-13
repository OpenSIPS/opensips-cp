<script language="JavaScript">
  
function confirmDelete()
{
 var agree=confirm("Are you sure you want to delete this entry?");
 if (agree)	return true;
  else return false;
}

function setReadonly(selectElementId){
        var selectElement = document.getElementById(selectElementId);
	if (typeof(selectElement) == 'undefined' || selectElement == null)
		return;
        if (selectElement){
                var parent = selectElement.parentElement;
                var textValue = selectElement.options[selectElement.options.selectedIndex].innerText;
                if (!parent){
                        parent=selectElement.parentNode;
                        textValue = selectElement.options[selectElement.options.selectedIndex].text;
                }
                var input = document.createElement("input");
                input.setAttribute("id",selectElement.id);
                input.setAttribute("type","hidden");
                input.setAttribute("value",textValue);
                input.style.background="#cccccc";
                input.readOnly = true;
                parent.appendChild(input);
        }
        selectElement.style.display="none";
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


function centerMe(element) {
	//pass element name to be centered on screen
	var pWidth = window.innerWidth;
	var pTop =  window.scrollTop;
	var eWidth = document.getElementById(element).style.width
	var height = document.getElementById(element).style.height
	document.getElementById(element).style.top = '250px';
	//$(element).css('top',pTop+100+'px')
	document.getElementById(element).style.left = parseInt((pWidth / 2) - 205) + 'px';
}



function closeDialog() {
	document.getElementById('overlay').style.display = 'none';
	document.getElementById('dialog').style.display = 'none';
	document.getElementById('dialog').innerHTML = '';
}

function apply_changes(){
		url = "apply_changes.php";
		
		var http = getHTTPObject();
		
		http.open("GET", url, false);
		http.onreadystatechange = handleHttpResponse(http);
		http.send(null);
		result = http.responseText;
		
		var body = document.body,
    	html = document.documentElement;

		var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );


		document.getElementById('overlay').style.height = height;
		document.getElementById('overlay').style.display = 'block';
		document.getElementById('dialog').innerHTML = result;
		centerMe('dialog')
		document.getElementById('overlay').onclick = function () {closeDialog();};
		document.getElementById('dialog').style.display = 'block';
		return true;
		

		document.getElementById("content").innerHTML = "whatever";
		
		
	return true;
}

function updateFilterCombo(update_combo, filter_combo)
{
	var f = document.getElementById(filter_combo);
	var forValue = (f.options[f.selectedIndex].value != ""?
		f.options[f.selectedIndex].value:f.options[f.selectedIndex].text);
	var fields = document.getElementById(update_combo),i,val;
	var show = null;

	for(i = 0; i < fields.length; i++) {
		val = fields[i];
		if (forValue!="Empty..." && val.getAttribute('hook')!="Empty..." && val.getAttribute('hook')!=forValue) {
			val.hidden = true;
			if (val.selected)
				val.selected=false;
		} else {
			val.hidden = false;
			if (show == null)
				show = val.value;
		}
	}
	fields.value = show;
}

</script>
