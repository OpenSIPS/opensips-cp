<script language="JavaScript">
  


function addWidget(grid, content, sizeX, sizeY, col = null, row = null) {
    var widgetElement = content;
    var widget = [widgetElement, sizeX, sizeY, col, row];
    gridster.add_widget.apply(gridster, widget);
}
function move(oldID, newID) {
    var newParent = document.getElementById(newID);
    var oldParent = document.getElementById(oldID);
  while (oldParent.childNodes.length > 0) {
    newParent.appendChild(oldParent.childNodes[0]);
  }
}

function store_dashboard(arg) {
  const Http = new XMLHttpRequest();
  const url = 'store_dashboard.php';
  Http.open("POST", url);
  Http.setRequestHeader('Content-type', 'application/json');
  Http.send(JSON.stringify(arg));
  Http.onreadystatechange =(e) => {
    //console.log(Http.responseText);
  } 
}

function getChartHtml() {
  const Http = new XMLHttpRequest();
  //const url = 'dashboard3.php';
  Http.open("GET", url);
  Http.setRequestHeader('Content-type', 'text/html');
  Http.send(null);
  Http.onreadystatechange =(e) => {
   // console.log(Http.responseText);
  }
}

function lockPanel() {
  if (gridster.drag_api.disabled) {
    gridster.enable();
  }
  else {
    gridster.disable();
  }
  var test = document.getElementsByClassName('gridster');
  //test[0].style = "background-color: #ffffff";
  //console.log(test[0].style);
}
</script>