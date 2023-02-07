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

function remove_content(id) {
  var content = document.getElementById(id);
  content.innerHTML = '';
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
//box-shadow: -6px 0px 10px -5px rgba(0, 0, 0, 0.4);
function lockPanel() {
  if (gridster.drag_api.disabled) {
    const edit_btn = document.getElementById('panel_buttons');
    edit_btn.style.opacity = "1";
    const btn = document.getElementById('lockButton');
    btn.style.content = "url('../../../images/dashboard/unlock.png')";
    //gridster.enable_resize();
    gridster.enable();
    const menus = document.getElementsByClassName('dashboard_menu');
    for(const menu of menus) { //display menu in editing mode
      menu.style.display = 'block';
    }
    const editables = document.getElementsByClassName('dashboard_edit');
    for(const editable of editables) { //change shadow in editing mode
      editable.style['box-shadow'] = '-6px 0px 10px 0px rgba(0, 0, 0, 0.4)';
    }
    const editables_body = document.getElementsByClassName('dashboard_edit_body');
    for(const editable_body of editables_body) { //change widget body corners when menu is active
      editable_body.style['border-radius'] = '0px 0px 7px 7px';
    }
    const title_bars = document.getElementsByClassName('widget_title_bar');
    for(const title_bar of title_bars) { //change widget body corners when menu is active
      title_bar.style['border-radius'] = '0px 0px 1px 1px';
    }
  }
  else {
    const edit_btn = document.getElementById('panel_buttons');
    edit_btn.style.opacity = "0";
    const btn = document.getElementById('lockButton');
    btn.style.content = "url('../../../images/dashboard/lock.png')";
    gridster.disable_resize();
    gridster.disable();
    const menus = document.getElementsByClassName('dashboard_menu');
    for(const menu of menus) {
      menu.style.display = 'none';
    }
    const editables = document.getElementsByClassName('dashboard_edit');
    for(const editable of editables) {
      editable.style['box-shadow'] = '0 0 5px rgb(0 0 0 / 30%)';
    }
    const editables_body = document.getElementsByClassName('dashboard_edit_body');
    for(const editable_body of editables_body) {
      editable_body.style['border-radius'] = '7px 7px 7px 7px';
    }
    const title_bars = document.getElementsByClassName('widget_title_bar');
    for(const title_bar of title_bars) { //change widget body corners when menu is active
      title_bar.style['border-radius'] = '7px 7px 1px 1px';
    }
  }
}


function show_widget(widget_path){
  url = "dashboard.import_details.php?widget_dir="+widget_path;

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
  document.getElementById('widget_overlay').innerHTML = result;
  centerMe('widget_overlay')
    document.getElementById('overlay').onclick = function () {closeOverlay();};
  document.getElementById('widget_overlay').style.display = 'block';
  window.location.hash = '#tab1';
  location.hash = "tab1";

}

function refresh_widget_status(status, widget_id) {
  var status_indicator = document.getElementById(widget_id + "_status_indicator");
  status_indicator.className = "status-indicator status" + status;
}

async function fetch_widget_data(widget_id) {
	  let response = await fetch("dashboard.refresh.php?id="+widget_id);
	  if (response.status === 200) {
      let data = await response.text();
      return data;
	  }
    return null;
}

async function fetch_widget_info(widget_type, cmd, params) {
  var url = "dashboard.info.php?widget_type="+widget_type+"&widget_command="+cmd;
  if (params != undefined && params.length != 0)
    url += "&"+params;
  let response = await fetch(url);
  if (response.status === 200) {
     let data = await response.json();
     return data;
  }
  return null;
}

function refresh_widget_json(widget_id, func) {

  fetch_widget_data(widget_id).then(data => {
    var response = JSON.parse(data);
    refresh_widget_status(response.status, widget_id);

    const element = document.getElementById(widget_id).getElementsByClassName('widget_body')[0];

    func(element, response.data);
  });
}

function refresh_widget_html(widget_id) {

  fetch_widget_data(widget_id).then(data => {
    var status = data.split('\n')[0];
    data = data.slice(status.length + 1);
    refresh_widget_status(status, widget_id);

    const element = document.getElementById(widget_id).getElementsByClassName('widget_body')[0];

    element.innerHTML = data;
  });

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



function closeOverlay() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('widget_overlay').style.display = 'none';
  document.getElementById('widget_overlay').innerHTML = '';
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

// vim:set sw=2 ts=2 et ft=php fdm=marker:
</script>
