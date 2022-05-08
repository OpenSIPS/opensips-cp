<!-- Code from d3-graph-gallery.com -->
<!DOCTYPE html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="d3.v4.min.js"></script>
<!-- Create a div where the graph will take place -->
<div id=<?=$_SESSION['stat']?>></div>

<div id="tooltipd3<?php echo $_SESSION['stat'] ?>" class="tooltipd3">
                <div class="tooltipd3-date">
                    <span id="date"></span>
                </div>
                <div class="tooltipd3-Internet">
                    Value: <span id="internet"></span>
                </div>
            </div>


<script>

display_graph("<?php echo $_SESSION['stat'] ?>", "<?php echo $_SESSION['full_stat'] ?>", "<?php echo $_SESSION['box_id_graph'] ?> ", "<?php echo $_SESSION['normal'] ?>");

function display_graph(arg1, arg2, arg3, arg4) {

//Read the data

d3.csv("get_data.php?stat=".concat(arg1).concat("&full_stat=").concat(arg2).concat("&box=").concat(arg3).concat("&normal=").concat(arg4),

  // When reading the csv, format variables:
  function(d){
    if (d.value == "f") {
      d.value = null;
    }
    return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value}
  },

  //  use this dataset:
  function(data) {
    var refresh = 1;
    var zoomTrigger = false;
  // set the dimensions and margins of the graph
  var margin = {top: 10, right: 30, bottom: 30, left: 50},
      width = 400 - margin.left - margin.right,
      height = 190 - margin.top - margin.bottom;

  // append the svg object to the body of the page
  var svg = d3.select("#".concat(arg1))
    .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .attr("id", arg2.concat("_position"))
    .append("g")
      .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")")
    .on("mousemove", onMouseMove)
    .on("mouseleave", onMouseLeave);        



    var xScale = d3.scaleTime()
      .domain(d3.extent(data, function(d) { return d.date; }))
      .range([ 0, width ]);

    function onMouseLeave() {
      tooltip.style("opacity", 0);
      tooltipCircle.style("opacity", 0);
    }

    function onMouseMove() {

      var yScale = d3.scaleLinear()
      .domain([0, d3.max(data, function(d) { return +d.value; })])
      .range([ height, 0 ]);

      const mousePosition = d3.mouse(this);
      const hoveredDate = xScale.invert(mousePosition[0]);

      const xAccessor = (d) => {
        if (d.value == null)
          return null;
        else return d.date;
      }
      const yAccessor = (d) => +d.value;

      const getDistanceFromHoveredDate = (d) =>
      Math.abs(xAccessor(d) - hoveredDate);
    const closestIndex = d3.scan(
      data,
      (a, b) => getDistanceFromHoveredDate(a) - getDistanceFromHoveredDate(b)
    );
    const closestDataPoint = data[closestIndex];

    const closestXValue = xAccessor(closestDataPoint);
    const closestYValue = yAccessor(closestDataPoint);

    const formatDate = d3.timeFormat("%c");
    tooltip.select("#date").text(formatDate(closestXValue));

    const formatYvalue = (d) => d;
    tooltip.select("#internet").html(formatYvalue(closestYValue));

    var offsets = document.getElementById(arg2.concat("_position")).getBoundingClientRect();
    const x = xScale(closestXValue) + offsets.left + margin.left ;
    const y = yScale(closestYValue) + offsets.top + window.pageYOffset - 85;

    tooltip.style(
      "transform",
      `translate(` + `calc( -50% + ${x}px),` + `calc(${y}px)` + `)`
    );
    
    tooltip.style("opacity", 1);
    tooltip.style("width", "220px");

    tooltipCircle
      .attr("cx", xScale(closestXValue))
      .attr("cy", yScale(closestYValue))
      .style("opacity", 1);
    }

    // Add X axis --> it is a date format
    var x = d3.scaleTime()
      .domain(d3.extent(data, function(d) { return d.date; }))
      .range([ 0, width ]);
    var xAxis = svg.append("g")
     .attr("class", "xAxis")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x).ticks(5));

    // Add Y axis
    var y = d3.scaleLinear()
      .domain([0, d3.max(data, function(d) { return +d.value; })])
      .range([ height, 0 ]);
    var yAxis = svg.append("g")
     .attr("class", "yAxis")
      .call(d3.axisLeft(y)
      .tickFormat(d3.format(".2s")));

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

    // Add a clipPath: everything out of this area won't be drawn.
    var clip = svg.append("defs").append("svg:clipPath")
        .attr("id", "clip")
        .append("svg:rect")
        .attr("width", width )
        .attr("height", height )
        .attr("x", 0)
        .attr("y", 0);



    // Add brushing
    var brush = d3.brushX()                   // Add the brush feature using the d3.brush function
        .extent( [ [0,0], [width,height] ] )  // initialise the brush area: start at 0,0 and finishes at width,height: it means I select the whole graph area
        .on("end", updateChart)               // Each time the brush selection changes, trigger the 'updateChart' function

    // Create the line variable: where both the line and the brush take place
    var line = svg.append('g')
      .attr("clip-path", "url(#clip)")



    // Add the line
    line.append("path")
      .datum(data)
      .attr("class", "line")  // I add the class line to be able to modify this line later on.
      .attr("fill", "none")
      .attr("stroke", "steelblue")
      .attr("stroke-width", 1.5)
      .attr("d", d3.line()
        .curve(d3.curveMonotoneX)
        .x(function(d) { return x(d.date) }) 
        .y(function(d) { return y(d.value) })
        .defined(function (d) { if (d.value != null) return true; else return false; })
        )

    // Add the brushing
    line
      .append("g")
        .attr("class", "brush")
        .call(brush);

    // A function that set idleTimeOut to null
    var idleTimeout
    function idled() { idleTimeout = null; }

    // A function that update the chart for given boundaries
    function updateChart() {
      refresh = 0;
      // What are the selected boundaries?
      var extent = d3.event.selection

      // If no selection, back to initial coordinate. Otherwise, update X axis domain
      if(!extent){
        if (!idleTimeout) return idleTimeout = setTimeout(idled, 350); 
        x.domain([ 4,8])
      }else{
        xScale.domain([ x.invert(extent[0]), x.invert(extent[1]) ])
        x.domain([ x.invert(extent[0]), x.invert(extent[1]) ])
        line.select(".brush").call(brush.move, null)
      }

      // Update axis and line position
      xAxis.transition().duration(1000).call(d3.axisBottom(x).ticks(5))
      line
          .select('.line')
          .transition()
          .duration(1000)
          .attr("d", d3.line()
            .curve(d3.curveMonotoneX)
            .x(function(d) { return x(d.date) })
            .y(function(d) { return y(d.value) })
            .defined(function (d) { if (d.value != null) return true; else return false; })
          )


          svg.selectAll("g.xAxis line.gridline").remove();
        
         svg.selectAll("g.xAxis g.tick") 
        .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", -height)
            .attr("x2", 0)
            .attr("y2", 0);
    }

    // If user double click, reinitialize the chart
    svg.on("dblclick",function(){
      refresh = 1;
      if (zoomTrigger == false) {
        zoomTrigger = true;
        updateGr();
      }
      x.domain(d3.extent(data, function(d) { return d.date; }))
      xScale.domain(d3.extent(data, function(d) { return d.date; }))
      xAxis.transition().call(d3.axisBottom(x).ticks(5))
      line
        .select('.line')
        .transition()
        .attr("d", d3.line()
          .curve(d3.curveMonotoneX)
          .x(function(d) { return x(d.date) })
          .y(function(d) { return y(d.value) })
          .defined(function (d) { if (d.value != null) return true; else return false; })
      )
            
        d3.selectAll("g.xAxis line.gridline").remove();

         d3.selectAll("g.xAxis g.tick") 
        .append("line") 
            .attr("class", "gridline")
            .attr("x1", 0) 
            .attr("y1", -height)
            .attr("x2", 0)
            .attr("y2", 0);
    });


    
  const tooltip = d3.select("#tooltipd3".concat(arg1));
  const tooltipCircle = svg
    .append("circle")
    .attr("class", "tooltipd3-circle")
    .attr("r", 4)
    .attr("stroke", "#af9358")
    .attr("fill", "white")
    .attr("stroke-width", 2)
    .style("opacity", 0);


    var intervalID = window.setInterval(updateGr, 3000);


    function updateGr() {
      if( refresh == 1) {
        d3.csv("get_data.php?stat=".concat(arg1).concat("&full_stat=").concat(arg2).concat("&box=").concat(arg3).concat("&zoomOut=").concat(zoomTrigger).concat("&normal=").concat(arg4),

        // When reading the csv, I must format variables:
        function(d){
          if (d.value == "f") {
            d.value = null;
          }
          return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value}
        },

        // Now I can use this dataset:
        function(data2) { 
          data = data2;
          x.domain(d3.extent(data2, function(d) { return d.date; }))
          xScale.domain(d3.extent(data2, function(d) { return d.date; }))
          xAxis.transition().call(d3.axisBottom(x).ticks(5))
          y
          .domain([0, d3.max(data, function(d) { return +d.value; })])
          .range([ height, 0 ]);
          yAxis 
          .transition()
            .call(d3.axisLeft(y)
            .tickFormat(d3.format(".2s")));
          line
            .select('.line')
            .datum(data2)
            .transition()
            .attr("d", d3.line()
              .curve(d3.curveMonotoneX)
              .x(function(d) { return x(d.date) })
              .y(function(d) { return y(d.value) })
              .defined(function (d) { if (d.value != null) return true; else return false; })
          )
                
            d3.selectAll("g.xAxis line.gridline").remove();

            d3.selectAll("g.xAxis g.tick") 
            .append("line") 
                .attr("class", "gridline")
                .attr("x1", 0) 
                .attr("y1", -height)
                .attr("x2", 0)
                .attr("y2", 0);
        
        })
    }

      
    }

})
}


</script>
