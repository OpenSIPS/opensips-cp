
<!DOCTYPE html>
<meta charset="utf-8">

<script src="../../../common/charting/d3.v4.min.js"></script>
<div id=<?=$_SESSION['id']?>>

<div id="tooltipd3<?php echo $_SESSION['id'] ?>" class="tooltipd3">
                <div class="tooltipd3-date">
                    <span id="date"></span>
                </div>
                <div class="tooltipd3-Internet">
                    Value: <span id="internet"></span>
                </div>
            </div>
</div>

<script>

display_graph("<?=$_SESSION['id']?>", "<?=$_SESSION['stat']?>", "<?=$_SESSION['box_id_graph']?> ", "<?=$_SESSION['normal']?>", "<?=$_SESSION['dashboard_active']?>", "<?=$_SESSION['widget_chart_size']?>", <?=$_SESSION['refreshInterval']?>);

function display_graph(id, stat, arg3, arg4, arg5, arg6, refreshInterval) {
data_url = "../../../common/charting/get_data.php?id=".concat(id).concat("&stat=").concat(stat).concat("&box=").concat(arg3).concat("&normal=").concat(arg4);
if (arg5 == 1)
    data_url = data_url.concat("&chart_size=").concat(arg6);
d3.csv(data_url,
  function(d){
    if (d.value == "f") {
      d.value = null;
    }
    return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value}
  },

  function(data) { 
    var refresh = 1;
    var zoomTrigger = false;
	
  if (arg5 == 1) {
	var margin = {top: 10, right: 30, bottom: 30, left: 30},
		width = 400 - margin.left - margin.right,
		height = 190 - margin.top - margin.bottom;
  } else {
	var margin = {top: 10, right: 30, bottom: 30, left: 50},
		width = 660 - margin.left - margin.right, 
		height = 300 - margin.top - margin.bottom;
  }

  var svg = d3.select("#".concat(id))
    .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .attr("id", stat.concat("_position"))
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
	  var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
      var yScale = d3.scaleLinear()
      .domain([0, 1.1 * d3.max(data, function(d) { return +d.value; })])
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

    const formatYvalue = (d) => d3.format(".4s")(d.toFixed(2));
    tooltip.select("#internet").html(formatYvalue(closestYValue));
    var offsets = document.getElementById(stat.concat("_position")).getBoundingClientRect();
	if (arg5 == 0) {
		const x = xScale(closestXValue) + offsets.left + margin.left ;
		const y = yScale(closestYValue) + offsets.top + window.pageYOffset - 85;
		tooltip.style(
		"transform",
		`translate(` + `calc( -50% + ${x}px),` + `calc(${y}px)` + `)`
		);
	} else {
		const x = xScale(closestXValue) - 98 ;
		const y = yScale(closestYValue) + window.pageYOffset - 75 - scrollTop;
		tooltip.style(
		"transform",
		`translate(` + `calc( ${x}px),` + `calc(${y}px)` + `)`
		);		
	}
    
    tooltip.style("opacity", 1);
    tooltip.style("width", "220px");

    tooltipCircle
      .attr("cx", xScale(closestXValue))
      .attr("cy", yScale(closestYValue))
      .style("opacity", 1);
    }

    var x = d3.scaleTime()
      .domain(d3.extent(data, function(d) { return d.date; }))
      .range([ 0, width ]);
    var xAxis = svg.append("g")
     .attr("class", "xAxis")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x).ticks(5));

    var y = d3.scaleLinear()
      .domain([0, 1.1 * d3.max(data, function(d) { return +d.value; })])
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

    var clip = svg.append("defs").append("svg:clipPath")
        .attr("id", "clip")
        .append("svg:rect")
        .attr("width", width )
        .attr("height", height )
        .attr("x", 0)
        .attr("y", 0);



    var brush = d3.brushX()
        .extent( [ [0,0], [width,height] ] ) 
        .on("end", updateChart)     

		
    var line = svg.append('g')
      .attr("clip-path", "url(#clip)")

    line.append("path")
      .datum(data)
      .attr("class", "line") 
      .attr("fill", "none")
      .attr("stroke", "steelblue")
      .attr("stroke-width", 1.5)
      .attr("d", d3.line()
        .curve(d3.curveMonotoneX)
        .x(function(d) { return x(d.date) }) 
        .y(function(d) { return y(d.value) })
        .defined(function (d) { if (d.value != null) return true; else return false; })
        )

    line
      .append("g")
        .attr("class", "brush")
        .call(brush);

    var idleTimeout
    function idled() { idleTimeout = null; }

    function updateChart() {
      refresh = 0;
      var extent = d3.event.selection

      if(!extent){
        if (!idleTimeout) return idleTimeout = setTimeout(idled, 350); 
        x.domain([ 4,8])
      }else{
        xScale.domain([ x.invert(extent[0]), x.invert(extent[1]) ])
        x.domain([ x.invert(extent[0]), x.invert(extent[1]) ])
        line.select(".brush").call(brush.move, null)
      }

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


    
  const tooltip = d3.select("#tooltipd3".concat(id));
  const tooltipCircle = svg
    .append("circle")
    .attr("class", "tooltipd3-circle")
    .attr("r", 4)
    .attr("stroke", "#af9358")
    .attr("fill", "white")
    .attr("stroke-width", 2)
    .style("opacity", 0);


    var intervalID = window.setInterval(updateGr, refreshInterval);


    function updateGr() {
      if( refresh == 1) {
        data_url = "../../../common/charting/get_data.php?id=".concat(id).concat("&stat=").concat(stat).concat("&box=").concat(arg3).concat("&zoomOut=").concat(zoomTrigger).concat("&normal=").concat(arg4);
        if (arg5 == 1)
            data_url = data_url.concat("&chart_size=").concat(arg6);
	refresh = 0;
        d3.csv(data_url,
        function(d){
          if (d.value == "f") {
            d.value = null;
          }
          return { date : d3.timeParse("%Y-%m-%d-%H-%M-%S")(d.date), value : d.value}
        },

        function(data2) { 
          data = data2;
          x.domain(d3.extent(data2, function(d) { return d.date; }))
          xScale.domain(d3.extent(data2, function(d) { return d.date; }))
          xAxis.transition().call(d3.axisBottom(x).ticks(5))
          y
          .domain([0, 1.1 * d3.max(data, function(d) { return +d.value; })])
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
        
	});
	refresh = 1;
    }

      
    }

})
}


</script>
