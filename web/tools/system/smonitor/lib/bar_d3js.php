<!DOCTYPE html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="d3.v4.min.js"></script>

<!-- Create a div where the graph will take place -->
<div id="<?="chart_".$wi?>"></div>
<script>
function display_bar() {
  var wid = "<?=$wi?>";

  // set the dimensions and margins of the graph
  var margin = {top: 10, right: 30, bottom: 50, left: 40},
      width = 410 - margin.left - margin.right,
      height = 180 - margin.top - margin.bottom;

  // append the svg object to the body of the page
  var svg = d3.select("#chart_".concat(wid))
    .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

  // Parse the Data
  d3.csv("https://raw.githubusercontent.com/holtzy/data_to_viz/master/Example_dataset/7_OneCatOneNum_header.csv", function(data) {

  // X axis
  var x = d3.scaleBand()
    .range([ 0, width ])
    .domain(data.map(function(d) { return d.Country; }))
    .padding(0.2);
  svg.append("g")
    .attr("transform", "translate(0," + height + ")")
    .call(d3.axisBottom(x))
    .selectAll("text")
      .attr("transform", "translate(-10,0) rotate(-45)")
      .style("text-anchor", "end").style("font-size","7px");

  // Add Y axis
  var y = d3.scaleLinear()
    .domain([0, 13000])
    .range([ height, 0]);
  svg.append("g")
    .call(d3.axisLeft(y).ticks(3));

  // Bars
  svg.selectAll("mybar")
    .data(data)
    .enter()
    .append("rect")
      .attr("x", function(d) { return x(d.Country); })
      .attr("y", function(d) { return y(d.Value); })
      .attr("width", x.bandwidth())
      .attr("height", function(d) { return height - y(d.Value); })
      .attr("fill", "#69b3a2")

  })
}
display_bar();
</script>