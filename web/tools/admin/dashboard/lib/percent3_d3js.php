<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8 />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script src="http://d3js.org/d3.v2.js"></script>
  <link href="styles.css" rel="stylesheet" type="text/css" />
  <title>"Percent complete" bar</title>
</head>

<body>
  
  <div id="container"></div> <!--d3 chart gets inserted in this div-->
  
  <script>
  
  // based on this tutorial: http://mbostock.github.com/d3/tutorial/bar-1.html
  // with adjustments and stuff
  // see it here: http://jsbin.com/avemek/2/
    
  var data = [100, 35]; // here are the data values; v1 = total, v2 = current value
  
  var chart = d3.select("#container").append("svg") // creating the svg object inside the container div
    .attr("class", "chart")
    .attr("width", 300) // bar has a fixed width
    .attr("height", 100 * data.length)
    .attr('transform', "translate(" +0+ ", " + 30 + ")");
  
  var x = d3.scale.linear() // takes the fixed width and creates the percentage from the data values
    .domain([0, d3.max(data)])
    .range([0, 300]); 
  
  chart.selectAll("rect") // this is what actually creates the bars
    .data(data)
  .enter().append("rect")
    .attr("width", x)
    .attr("height", 60)
    .attr("rx", 5) // rounded corners
    .attr("ry", 5);
    


  chart.selectAll("text") // adding the text labels to the bar
    .data(data)
  .enter().append("text")
    .attr("x", x)
    .attr("y", 10) // y position of the text inside bar
    .attr("dx", -3) // padding-right
    .attr("dy", ".35em") // vertical-align: middle
    .attr("text-anchor", "end") // text-align: right
    .text(String)
    
	chart.append("text")
		.text(function () {
            return "load:load";
		})
		.attr('id', "Name")
		.attr('transform', "translate(" + 85 + ", " + 110+ ")")
		.style("fill", "#000000")
		.attr("font-size", 30);
  </script>
 
</body>
</html>