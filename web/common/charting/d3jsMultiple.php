<!-- Code from d3-graph-gallery.com -->
<!DOCTYPE html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="../../../common/charting/d3.v4.min.js"></script>
<!-- Create a div where the graph will take place -->
<div id=<?=$_SESSION['id']?>></div>

<div id="tooltipd3<?php echo $_SESSION['id'] ?>" class="tooltipd3">
                <div class="tooltipd3-date">
                    <span id="date"></span>
                </div>
                <div class="tooltipd3-Internet">
                    Value: <span id="internet"></span>
                </div>
            </div>


<script>

display_graphs("<?=$_SESSION['id']?>", <?=json_encode($_SESSION['stats'])?>, 
<?=json_encode($_SESSION['boxes_list'])?>, <?=json_encode($_SESSION['normal'])?>, "<?=$_SESSION['scale']?>", "<?=$_SESSION['dashboard_active']?>", "<?=$_SESSION['widget_chart_size']?>", <?=$_SESSION['refreshInterval']?>);

function display_graphs(id, stats, arg3, arg4, arg5, arg6, arg7, refreshInterval) {
  //   var stats_list = "";
    var stats_list = encodeURIComponent(JSON.stringify(stats));
    var box_list = encodeURIComponent(JSON.stringify(arg3));
    var normal_list = encodeURIComponent(JSON.stringify(arg4));
    var data_url = "../../../common/charting/get_multiple_data.php?id=".concat(id).concat("&stats=").concat(stats_list).concat("&box=").concat(box_list).concat("&normal=").concat(normal_list);
    if (arg6 == 1)
        data_url = data_url.concat("&chart_size=").concat(arg7);
d3.csv(data_url,

function(d){
    if (d.value == "f") {
      d.value = null;
    }
    return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value, name : d.name}
  },

 function(data) {
  var currentAxis = 0;
    var refresh = 1;
    var zoomTrigger = false;
    
  // set the dimensions and margins of the graph

 if (arg6 == 1) {
	var margin = {top: 10, right: 30, bottom: 30, left: 30},
		width = 400 - margin.left - margin.right,
		height = 220 - margin.top - margin.bottom;
  } else {
	var margin = {top: 10, right: 30, bottom: 100, left: 50},
      width = 660 - margin.left - margin.right,
      height = 370 - margin.top - margin.bottom;
  }


  // append the svg object to the body of the page
  var svg = d3.select("#".concat(id))
    .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .attr("id", stats.concat("_position"))
    .append("g")
      .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")")
    .on("mousemove", onMouseMove)
    .on("mouseleave", onMouseLeave);    

   var clip = svg.append("defs").append("svg:clipPath")
    .attr("id", "clip")
    .append("svg:rect")
    .attr("width", width )
    .attr("height", height )
    .attr("x", 0)
    .attr("y", 0);

    
    const tooltip = d3.select("#tooltipd3".concat(id));
    const tooltipCircle = svg
    .append("circle")
    .attr("class", "tooltipd3-circle")
    .attr("r", 4)
    .attr("stroke", "#af9358")
    .attr("fill", "white")
    .attr("stroke-width", 2)
    .style("opacity", 0);
    const labelX = 0;
    const labelY = 270;
    var removed = {};
    stats.forEach((element, i) => removed[element] = 0);
 

// group the data: I want to draw one line per group
var sumstat = d3.nest() // nest function allows to group the calculation per level of a factor
  .key(function(d) { return d.name;})
  .entries(data);


var res = sumstat.map(function(d){ return d.key }) // list of group names
var color = d3.scaleOrdinal()
.domain(res)
.range(['#e41a1c','#377eb8','#4daf4a','#984ea3','#ff7f00','#ffff33','#a65628','#f781bf','#999999'])  

// Add X axis --> it is a date format
var x = d3.scaleTime()
  .domain(d3.extent(data, function(d) { return d.date; }))
  .range([ 0, width ]);
var xAxis = svg.append("g")
  .attr("class", "xAxis")
  .attr("transform", "translate(0," + height + ")")
  .call(d3.axisBottom(x).ticks(5));

// Add Y axis
var y = {};
for(var i = 0 ; i < sumstat.length; i++) {
y[sumstat[i].key] = d3.scaleLinear()
  .domain([0, 1.1 * d3.max(sumstat[i].values, function(d) { return +d.value; })])
  .range([ height, 0 ]);
  }
yAll = d3.scaleLinear()
  .domain([0, 1.1 * d3.max(data, function(d) { return +d.value; })])
  .range([ height, 0 ]);
    
var yAxis;
if (arg5 == 1) {
    yAxis = svg.append("g")
    .attr("class", "yAxis")
    .attr("stroke", color(Object.keys(y)[0]))
    .call(d3.axisLeft(Object.values(y)[0]).tickFormat(d3.format(".2s")));
} else if (arg5 == 2){
    yAxis = svg.append("g")
    .attr("class", "yAxis")
    .call(d3.axisLeft(yAll).tickFormat(d3.format(".2s")));
}
yAxis
  .on("click", function() {
        if (arg5 == 1) {
            currentAxis ++;
            currentAxis = currentAxis % sumstat.length;
            yAxis.transition().duration(1000).call(d3.axisLeft(Object.values(y)[currentAxis]).tickFormat(d3.format(".2s")));
            svg.selectAll("g.yAxis line.gridline").remove();
            svg.selectAll("g.yAxis")
            .attr("stroke", color(Object.keys(y)[currentAxis]));
            svg.selectAll("g.yAxis g.tick") 
            .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", 0)
            .attr("x2", width)
            .attr("y2", 0);
        }
    });

  svg.selectAll("g.yAxis g.tick") 
        .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", 0)
            .attr("x2", width)
            .attr("y2", 0);
            
svg.selectAll("g.xAxis g.tick") 
    .append("line") 
        .attr("class", "gridline")
        .attr("x1", 0) 
        .attr("y1", -height)
        .attr("x2", 0)
        .attr("y2", 0);

// color palette

stats.forEach ((element, i) => {
    svg.append("circle").attr("cx",labelX + 230*Math.floor(i/2)).attr("cy",labelY + 30 + 30 * (i %2)).attr("r", 6).style("fill", color(element))
    .on("click", function() {
        if (arg5 == 1) {
            currentAxis = Object.keys(y).indexOf(element);
            yAxis.transition().duration(1000).call(d3.axisLeft(y[element]).tickFormat(d3.format(".2s")));
            svg.selectAll("g.yAxis line.gridline").remove();
            svg.selectAll("g.yAxis")
            .attr("stroke", color(element));
            svg.selectAll("g.yAxis g.tick") 
            .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", 0)
            .attr("x2", width)
            .attr("y2", 0);
        }
    })
	svg.append("text").attr("x", labelX  + 20 + 230*Math.floor(i/2)).attr("y", labelY + 30 + 30 * (i %2) ).text(stats[i]).style("font-size", "15px").attr("alignment-baseline","middle").attr("cursor", "pointer")
	.on( "click", function(d) {
    if(!removed[element]) {
        removed[element] = 1;
        update_opacity();
    }  else {
        removed[element] = 0;
        update_opacity();
    }
    });
})

// Draw the line
var lines = svg.selectAll(".line")
    .data(sumstat)
    .enter()
    .append("path")
      .attr("clip-path", "url(#clip)")
      .attr("fill", "none")
      .attr("stroke", function(d){ return color(d.key) })
      .attr("stroke-width", 1.5)
      .attr("d", function(d){
          var tempYscale = y[d.key];
          if (arg5 == 2) tempYscale = yAll;
        return d3.line()
          .x(function(d) { return x(d.date); })
          .y(function(d) {  return tempYscale(+d.value); })
          .defined(function (d) { 
              if (removed[d.name] == 1 ) return false;
              if (d.value != null) return true; else return false; 
            })
          (d.values)
      })


// start line brushing
    // Add brushing
    
    // A function that set idleTimeOut to null

    var idleTimeout;
    
    function idled() { idleTimeout = null; }

    var brush = d3.brushX()                   // Add the brush feature using the d3.brush function
        .extent( [ [0,0], [width,height] ] )  // initialise the brush area: start at 0,0 and finishes at width,height: it means I select the whole graph area
        .on("end", updateChart)   
    svg .append("g")
        .attr("class", "brush")
        .call(brush);


    function onMouseLeave() {
      tooltip.style("opacity", 0);
      tooltipCircle.style("opacity", 0);
    }

    function onMouseMove() {
	  var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
      const mousePosition = d3.mouse(this);
      const hoveredDate = x.invert(mousePosition[0]);
      var hoveredValue = {};
      const hoveredValueAll = yAll.invert(mousePosition[1]);
      
      if (arg5 == 1) {
        for (var i = 0 ; i < sumstat.length ; i ++) {
          hoveredValue[sumstat[i].key] = y[sumstat[i].key].invert(mousePosition[1]);
         
        } 
      }
      const xAccessor = (d) => {
        if (d.value == null)
          return null;
        else return d.date;
      }
      const yAccessor = (d) => +d.value;

      const getDistanceFromHoveredDate = (d) =>
        Math.abs(xAccessor(d) - hoveredDate);
        
      var closestIndex;
      var smallestYdist = -1;
      var closestDataPoint;
      for (var i = 0; i < sumstat.length; i++) { 
        var currentMax;
        if (arg5 == 1)
          currentMax = d3.max(sumstat[i].values, function(d) { return +d.value; }); //this could be computed only once
        else currentMax = 1;
        var currentChartClosest = d3.scan(
          sumstat[i].values,
          (a, b) => getDistanceFromHoveredDate(a) - getDistanceFromHoveredDate(b) //closest point on x-axis for current (i) chart
    ); 
        if(arg5 == 2 ) var hoveredValueTemp = hoveredValueAll;
        else var hoveredValueTemp = hoveredValue[sumstat[i].key];
        if(Math.abs(sumstat[i].values[currentChartClosest].value - hoveredValueTemp)/currentMax < smallestYdist || smallestYdist == -1) {
          closestDataPoint = sumstat[i].values[currentChartClosest];
          smallestYdist = Math.abs(sumstat[i].values[currentChartClosest].value - hoveredValueTemp)/currentMax;
        }
    }
    
    if (arg5 == 1) var yTemp = y[closestDataPoint.name];
    else var yTemp = yAll;

    const closestXValue = xAccessor(closestDataPoint);
    const closestYValue = yAccessor(closestDataPoint);

    const formatDate = d3.timeFormat("%c");
    tooltip.select("#date").text(formatDate(closestXValue));

    const formatYvalue = (d) => d;
    tooltip.select("#internet").html(formatYvalue(d3.format(".4s")(closestYValue)));

    var offsets = document.getElementById(stats.concat("_position")).getBoundingClientRect();

	if (arg6 == 0) {
		const xT = x(closestXValue) + offsets.left + margin.left ;
    const yT = yTemp(closestYValue) + offsets.top + window.pageYOffset - 85;

    tooltip.style(
      "transform",
      `translate(` + `calc( -50% + ${xT}px),` + `calc(${yT}px)` + `)`
    );
	} else {
		const xT = x(closestXValue) - 98 ;
		const yT = yTemp(closestYValue) + window.pageYOffset - 75 - scrollTop;
		tooltip.style(
		"transform",
		`translate(` + `calc( ${xT}px),` + `calc(${yT}px)` + `)`
		);		
	}
    
    tooltip.style("opacity", 1);
    tooltip.style("width", "220px");

    tooltipCircle
      .attr("cx", x(closestXValue))
      .attr("cy", yTemp(closestYValue))
      .style("opacity", 1);
    }



 function updateChart() {
      refresh = 0;
      // What are the selected boundaries?
      var extent = d3.event.selection

      // If no selection, back to initial coordinate. Otherwise, update X axis domain
      if(!extent){
        if (!idleTimeout) return idleTimeout = setTimeout(idled, 350); 
        x.domain([ 4,8])
      }else{
        x.domain([ x.invert(extent[0]), x.invert(extent[1]) ])
        svg.select(".brush").call(brush.move, null)
      }

      // Update axis and line position
      xAxis.transition().duration(1000).call(d3.axisBottom(x).ticks(5))

    lines
        .transition()
        .duration(1000)
        .attr("d", function(d) {
            var tempYscale = y[d.key];
            if (arg5 == 2) tempYscale = yAll;
            return d3.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) {  return tempYscale(+d.value); })
            .defined(function (d) { 
                 if (removed[d.name] == 1 ) return false;
                if (d.value != null) return true; else return false; })
            (d.values)
        });
      

        svg.selectAll("g.xAxis line.gridline").remove();
        
         svg.selectAll("g.xAxis g.tick") 
        .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", -height)
            .attr("x2", 0)
            .attr("y2", 0);
    }

svg.on("dblclick",function(){
      refresh = 1;
      if (zoomTrigger == false) {
        zoomTrigger = true;
        updateGr();
      }
      x.domain(d3.extent(data, function(d) { return d.date; }))
      xAxis.transition().call(d3.axisBottom(x).ticks(5))
      lines
        .transition()
        .duration(10)
        .attr("d", function(d) {
            var tempYscale = y[d.key];
            if (arg5 == 2) tempYscale = yAll;
            return d3.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) {  return tempYscale(+d.value); })
            .defined(function (d) { 
                if (removed[d.name] == 1 ) return false;
                if (d.value != null) return true; else return false; })
            (d.values)
        });
      
            
        svg.selectAll("g.xAxis line.gridline").remove();

         svg.selectAll("g.xAxis g.tick") 
        .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", -height)
            .attr("x2", 0)
            .attr("y2", 0);
    });  

    
    var intervalID = window.setInterval(updateGr, refreshInterval);

function updateGr(){ 
    if( refresh == 1 ) {
        data_url = "../../../common/charting/get_multiple_data.php?id=".concat(id).concat("&stats=").concat(stats_list).concat("&box=").concat(box_list).concat("&zoomOut=").concat(zoomTrigger).concat("&normal=").concat(normal_list);
        if (arg6 == 1)
            data_url = data_url.concat("&chart_size=").concat(arg7);
	refresh = 0;
        d3.csv(data_url,

        // When reading the csv, I must format variables:
        function(d){
          if (d.value == "f") {
            d.value = null;
          }
          return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value, name : d.name}
        },

        // Now I can use this dataset:
        function(data2) {  
          data = data2;
          x.domain(d3.extent(data2, function(d) { return d.date; }))
          xAxis.transition().call(d3.axisBottom(x).ticks(5))
                // Add Y axis
          sumstat = d3.nest() // nest function allows to group the calculation per level of a factor
            .key(function(d) { return d.name;})
            .entries(data);
        var y = {};
        for(var i = 0 ; i <sumstat.length; i++) {
            y[sumstat[i].key] = d3.scaleLinear()
            .domain([0, 1.1 * d3.max(sumstat[i].values, function(d) { return +d.value; })])
            .range([ height, 0 ]);
        }
        yAll = d3.scaleLinear()
        .domain([0, 1.1 * d3.max(data, function(d) { return +d.value; })])
        .range([ height, 0 ]);

        lines
        .transition()
        .duration(1000)
        .attr("d", function(d) {
            var tempYscale = y[d.key];
            if (arg5 == 2) tempYscale = yAll;
            return d3.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) {  return tempYscale(+d.value); })
            .defined(function (d) { 
                if (removed[d.name] == 1 ) return false;
                if (d.value != null) return true; else return false; })
            (d.values)
        });
      
            svg.selectAll("g.xAxis line.gridline").remove();

            svg.selectAll("g.xAxis g.tick") 
            .append("line") 
                .attr("class", "gridline")
                .attr("x1", 0) 
                .attr("y1", -height)
                .attr("x2", 0)
                .attr("y2", 0);
        
	});
	refresh = 1;
    }
}; 
function update_opacity() {
    lines
        .transition()
        .duration(1000)
        .attr("d", function(d) {
            var tempYscale = y[d.key];
            if (arg5 == 2) tempYscale = yAll;
            return d3.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) {  return tempYscale(+d.value); })
            .defined(function (d) { 
                if (removed[d.name] == 1 ) return false;
                if (d.value != null) return true; else return false; })
            (d.values)
        });
}
})
}

</script>
