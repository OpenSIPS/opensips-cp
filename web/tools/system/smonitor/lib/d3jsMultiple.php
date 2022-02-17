<!-- Code from d3-graph-gallery.com -->
<!DOCTYPE html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="https://d3js.org/d3.v4.min.js"></script>
<!-- Create a div where the graph will take place -->
<div id=<?=$_SESSION['chart_group_id']?>></div>

<div id="tooltipd3<?php echo $_SESSION['chart_group_id'] ?>" class="tooltipd3">
                <div class="tooltipd3-date">
                    <span id="date"></span>
                </div>
                <div class="tooltipd3-Internet">
                    Value: <span id="internet"></span>
                </div>
            </div>


<script>

display_graphs("<?php echo $_SESSION['chart_group_id'] ?>", <?php echo json_encode($_SESSION['full_stats']) ?>, 
"<?php echo $_SESSION['box_id_graph'] ?> ", <?php echo json_encode($_SESSION['normal']) ?>, "<?php echo $_SESSION['scale'] ?>");

function display_graphs(arg1, arg2, arg3, arg4, arg5) {
    var refresh = 1;
    var zoomTrigger = false;
  // set the dimensions and margins of the graph
  var margin = {top: 10, right: 30, bottom: 100, left: 50},
      width = 660 - margin.left - margin.right,
      height = 370 - margin.top - margin.bottom;

  // append the svg object to the body of the page
  var svg = d3.select("#".concat(arg1))
    .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .attr("id", arg2.concat("_position"))
    .append("g")
      .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")")

   var clip = svg.append("defs").append("svg:clipPath")
    .attr("id", "clip")
    .append("svg:rect")
    .attr("width", width )
    .attr("height", height )
    .attr("x", 0)
    .attr("y", 0);

    const labelX = 0;
    const labelY = 270;
    var removed = {};
    arg2.forEach((element, i) => removed[element] = 0);
 //   var stats_list = "";
    var stats_list = encodeURIComponent(JSON.stringify(arg2));
    
d3.csv("get_multiple_data.php?statID=".concat(arg1).concat("&full_stats=").concat(stats_list).concat("&box=").concat(arg3).concat("&normal=").concat(arg4),

function(d){
    if (d.value == "f") {
      d.value = null;
    }
    return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value, name : d.name}
  },

 function(data) {

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
  .domain([0, d3.max(sumstat[i].values, function(d) { return +d.value; })])
  .range([ height, 0 ]);
  }
yAll = d3.scaleLinear()
  .domain([0, d3.max(data, function(d) { return +d.value; })])
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

arg2.forEach ((element, i) => {
    svg.append("circle").attr("cx",labelX + 230*Math.floor(i/2)).attr("cy",labelY + 30 + 30 * (i %2)).attr("r", 6).style("fill", color(element))
    .on("click", function() {
        if (arg5 == 1) {
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
    svg.append("text").attr("x", labelX  + 20 + 230*Math.floor(i/2)).attr("y", labelY + 30 + 30 * (i %2) ).text(arg2[i]).style("font-size", "15px").attr("alignment-baseline","middle").attr("cursor", "pointer")
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

    
    var intervalID = window.setInterval(updateGr, 3000);

function updateGr(){ 
    if( refresh == 1 ) {
        d3.csv("get_multiple_data.php?statID=".concat(arg1).concat("&full_stats=").concat(stats_list).concat("&box=").concat(arg3).concat("&zoomOut=").concat(zoomTrigger).concat("&normal=").concat(arg4),

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
            .domain([0, d3.max(sumstat[i].values, function(d) { return +d.value; })])
            .range([ height, 0 ]);
        }
        yAll = d3.scaleLinear()
        .domain([0, d3.max(data, function(d) { return +d.value; })])
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
        
        })
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