<script language="JavaScript">
  


function addWidget(grid, title, content, idWidget, sizeX, sizeY) {
    var widgetElement = '<li id='.concat(idWidget).concat('><header>').concat(title).concat('</header>').concat(content).concat('</li>');
    var widget = [widgetElement, sizeX, sizeY];
    gridster.add_widget.apply(gridster, widget);
    $.post( "dashboard.php", { namee: "John", time: "2pm" } );

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
    console.log(Http.responseText);
  }
  
}
</script>