<!DOCTYPE html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="https://d3js.org/d3.v4.js"></script>

<!-- Create a div where the graph will take place -->
<div id=<?=$_SESSION['ru_widget_id']?>></div>

<!-- Color scale -->
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script>

var width = 490
    height = 350
    margin = 40

var radius = Math.min(290, height) / 2 - margin
var total = "<?php echo $_SESSION['total_subs'] ?>";
var reg = "<?php echo $_SESSION['reg_subs'] ?>";
var contacts = "<?php echo $_SESSION['reg_contacts'] ?>";

var svg = d3.select("#" + "<?=$_SESSION['ru_widget_id']?>")
  .append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("transform", "translate(" + (1 - 50)+ "," + 0 + ")")
  .append("g")
    .attr("transform", "translate(" + 160 + "," + (height / 2 - 40) + ")");

var data = {"Reg. Users": reg, "": total-reg}

var color = d3.scaleOrdinal()
  .domain(data)
  .range(d3.schemeSet2);

var pie = d3.pie()
  .value(function(d) {return d.value; })
var data_ready = pie(d3.entries(data))

var arcGenerator = d3.arc()
  .innerRadius(0)
  .outerRadius(radius)

svg
  .selectAll('mySlices')
  .data(data_ready)
  .enter()
  .append('path')
    .attr('d', arcGenerator)
    .attr('fill', function(d){ return(color(d.data.key)) })
    .attr("stroke", "black")
    .style("stroke-width", "2px")
    .style("opacity", 0.7)

svg
  .selectAll('mySlices')
  .data(data_ready)
  .enter()
  .append('text')
  .text(function(d){ return d.data.key + " " + (d.data.value/total * 100).toFixed(2) + "%"})
  .attr("transform", function(d) { return "translate(" + arcGenerator.centroid(d) + ")";  })
  .style("text-anchor", "middle")
  .style("font-size", 17)

  svg.append("text").attr("x", 120).attr("y", -110 ).text("Total subscribers: " + total).style("font-size", "13px").attr("alignment-baseline","middle").attr("cursor", "pointer");
    
  svg.append("text").attr("x", 120).attr("y", -85 ).text("Registered subs: " + reg).style("font-size", "13px").attr("alignment-baseline","middle").attr("cursor", "pointer");
    
  svg.append("text").attr("x", 120).attr("y", -60 ).text("Registered contacts: " + contacts).style("font-size", "13px").attr("alignment-baseline","middle").attr("cursor", "pointer");
    
</script>