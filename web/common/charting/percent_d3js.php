
<script type="text/javascript" src="https://d3js.org/d3.v3.min.js"></script>
<div class="chart-gauge" id="gauge_<?=$_SESSION['gauge_id']?>"></div>
<script>
// D3.js Gauge Chart //
// Data which need to be fetched

display_indicator("<?=$_SESSION['gauge_id']?>", "<?=$_SESSION['gauge_value']?>", "<?=$_SESSION['gauge_max']?>", "<?=$_SESSION['warning']?>",  "<?=$_SESSION['critical']?>", <?=($_SESSION['refreshInterval'] != null)?$_SESSION['refreshInterval']:'null'?>);

function nFormatter(num, digits) {
  const lookup = [
    { value: 1, symbol: "" },
    { value: 1e3, symbol: "k" },
    { value: 1e6, symbol: "M" },
    { value: 1e9, symbol: "G" },
    { value: 1e12, symbol: "T" },
    { value: 1e15, symbol: "P" },
    { value: 1e18, symbol: "E" }
  ];
  const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
  var item = lookup.slice().reverse().find(function(item) {
    return num >= item.value;
  });
  return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
}

function display_indicator(id, value, gaugeMaxValue, warning, critical, refreshInterval) {

    var percentValue = Math.min(value, gaugeMaxValue) / gaugeMaxValue;
    var needleClient;
    var repaint_indicator = (function () {
        var barWidth, chart, chartInset, degToRad, repaintGauge, height, margin, numSections, padRad, percToDeg, percToRad, percent, radius, sectionIndx, svg, totalPercent, width, recalcPointerPos;

        percent = percentValue;

        numSections = 1;
        sectionPerc = 1 / numSections / 2;
        padRad = 0.025;
        chartInset = 10;

        // Orientation of Gauge:
        totalPercent = .75;

        el = d3.select("#gauge_" + id);

        margin = {
            top: 20,
            right: 100,
            bottom: 20,
            left: 40
        };

        width = 150;
        height = width;
        radius = Math.min(width, height) / 2;
        barWidth = 20 * width / 150;

        // Utility methods 
        percToDeg = function (perc) {
            return perc * 360;
        };

        percToRad = function (perc) {
            return degToRad(percToDeg(perc));
        };

        degToRad = function (deg) {
            return deg * Math.PI / 180;
        };

        // Create SVG element
        svg = el.append('svg')
		.attr('transform', "translate(" + (0) + ", " + (0) + ")")
		.attr('width', width + margin.left + margin.right ).attr('height', height / 1.5 + margin.top + margin.bottom);		// height/1.5 To Remove Extra Bottom Space

        // Add layer for the panel
        chart = svg.append('g').attr('transform', "translate(" + ((width + margin.left) / 2 ) + ", " + ((height + margin.top) / 2) + ")");

        chart.append('path').attr('class', "arc chart-first").attr('id', id+"-chart-first");
        chart.append('path').attr('class', "arc chart-second").attr('id', id+"-chart-second");
        chart.append('path').attr('class', "arc chart-third").attr('id', id+"-chart-third");

        arc3 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)
        arc2 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)
        arc1 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)

        repaintGauge = function (id) {
            perc = 0.5;
            var next_start = totalPercent;
            arcStartRad = percToRad(next_start);
            arcEndRad = arcStartRad + percToRad(perc * (warning / 100));
            next_start += perc * warning / 100;

            arc1.startAngle(arcStartRad).endAngle(arcEndRad);

            arcStartRad = percToRad(next_start);
            arcEndRad = arcStartRad + percToRad( perc * (critical - warning) / 100);
            next_start += perc * (critical - warning) / 100;

            arc2.startAngle(arcStartRad + padRad).endAngle(arcEndRad);

            arcStartRad = percToRad(next_start);
            arcEndRad = arcStartRad + percToRad( perc - (critical / 200));

            arc3.startAngle(arcStartRad + padRad).endAngle(arcEndRad);

            chart.select("#"+id+"-chart-first").attr('d', arc1);
            chart.select("#"+id+"-chart-second").attr('d', arc2);
            chart.select("#"+id+"-chart-third").attr('d', arc3);
        }	
        
        var Needle = (function () {

            //Helper function that returns the `d` value for moving the needle
            var recalcPointerPos = function (perc) {
                var centerX, centerY, leftX, leftY, rightX, rightY, thetaRad, topX, topY;
                thetaRad = percToRad(perc / 2);
                centerX = 0;
                centerY = 0;
                topX = centerX - this.len * Math.cos(thetaRad);
                topY = centerY - this.len * Math.sin(thetaRad);
                leftX = centerX - this.radius * Math.cos(thetaRad - Math.PI / 2);
                leftY = centerY - this.radius * Math.sin(thetaRad - Math.PI / 2);
                rightX = centerX - this.radius * Math.cos(thetaRad + Math.PI / 2);
                rightY = centerY - this.radius * Math.sin(thetaRad + Math.PI / 2);
                return "M " + leftX + " " + leftY + " L " + topX + " " + topY + " L " + rightX + " " + rightY;
            };

            function Needle(el, id) {
                this.el = el;
                this.id = id;
		this.inited = false;
                this.len = width / 2.5;
                this.radius = this.len / 8;
            }

            Needle.prototype.render = function () {
                this.el.append('circle').attr('class', 'needle-center').attr('cx', 0).attr('cy', 0).attr('r', this.radius);
                return this.el.append('path').attr('class', 'needle').attr('id', this.id + '-client-needle').attr('d', recalcPointerPos.call(this, 0));
            };

            Needle.prototype.moveTo = function (perc) {
                var self, oldValue = this.perc || 0;
                this.perc = perc;
                self = this;

		if (!this.inited) {
		    // Reset pointer position
                    this.el.transition().delay(100).ease('quad').duration(200).select('#'+this.id+'-client-needle').tween('reset-progress', function () {
                        return function (percentOfPercent) {
                            var progress = (1 - percentOfPercent) * oldValue;
                            return d3.select(this).attr('d', recalcPointerPos.call(self, progress));
                        };
                    });
		    this.inited = true;
		}

               this.el.transition().delay(300).ease('bounce').duration(1500).select('#'+this.id+'-client-needle').tween('progress', function () {
                   return function (percentOfPercent) {
                       var progress = oldValue + (percentOfPercent * (perc - oldValue));

                       return d3.select(this).attr('d', recalcPointerPos.call(self, progress));
		   };
               });

            };

            Needle.prototype.update = function (val, max, peak) {
    		var percent = Math.min(val, max) / max;

		var trX = 80 - 80 * Math.cos(percToRad(percent / 2)) - 10 * Math.sin(percToRad(percent / 2))  - Math.cos(percToRad(percent / 2)) * Math.cos(percToRad(percent / 2)) * 10;
		var trY = 70 - 60 * Math.sin(percToRad(percent / 2));

		d3.selectAll("#"+needle.id+"_value")
			.text("".concat(nFormatter(val, 3)))
			.attr('transform', "translate(" + (trX) + ", " + trY + ")");
		d3.selectAll("#"+needle.id+"_percent")
                	.text("".concat((percent * 100).toFixed(2)).concat("%"));
		d3.selectAll("#"+needle.id+"_valueMax")
                	.text(nFormatter(max, 3));
        	this.moveTo(percent);
	    }

            return Needle;

        })();

        var dataset = [{
            metric: "",
            value: value
        }]

        var texts = svg.selectAll("text")
            .data(dataset)
            .enter();


        texts.append("text")
            .attr('id', id+"_value")
            .attr('font-size', 9)
            .style("fill", '#000000');
        texts.append("text")
            .attr('id', id+"_percent")
            .attr('x', '80')
            .attr('y', '105')
            .attr("font-size", 9)
            .style("fill", '#000000');

        texts.append("text")
            .text('0')
            .attr('id', id + '_valueMin')
	    .attr('x', ((width + margin.left) / 100 + 35))
	    .attr('y', ((height + margin.top) / 2 + 10))
            .attr("font-size", 9)
            .style("fill", "#000000");


        texts.append("text")
            .attr('id', id + '_valueMax')
	    .attr('x', ((width + margin.left) / 1.03 - 35))
	    .attr('y', ((height + margin.top) / 2 + 10))
	    .attr('text-anchor', 'middle')
            .attr("font-size", 9)
            .style("fill", "#000000");

        repaintGauge(id);
        needle = new Needle(chart, id);
        needle.render();

	needle.update(value, gaugeMaxValue);

    });
    repaint_indicator();

    async function fetchData(id) {
	    let response = await fetch("dashboard.refresh.php?id="+id);
	    if (response.status === 200) {
		    let data = await response.json();
		    return data;
	    }
    }

    if (refreshInterval != null) {
	    window.setInterval(function(needle) {
	  	fetchData(id).then(data=>{
			refresh_widget_status(data.status, id);
			needle.update(data.data[0], data.data[1]);
	    	});
	    }, refreshInterval, needle);
    }
}
</script>	
<script src="../../../common/charting/d3.v4.min.js"></script>
