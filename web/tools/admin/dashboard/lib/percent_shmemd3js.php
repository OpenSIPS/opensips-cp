
<script type="text/javascript" src="https://d3js.org/d3.v3.min.js"></script>
<div class="chart-gauge" id=<?=$_SESSION['gauge_id']?>></div>
<script>
display_indicator("<?php echo $_SESSION['gauge_value'] ?>", "<?php echo $_SESSION['gauge_id'] ?>", "<?php echo $_SESSION['gauge_max'] ?>", "<?php echo $_SESSION['warning'] ?>", "<?php echo $_SESSION['critical'] ?>", "<?php echo $_SESSION['max_ever'] ?>");

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

function display_indicator(arg1, arg2, arg3, arg4, arg5, arg6) {
	var warning = arg4;
	var critical = arg5;
    var name = "";
    var value = arg1;
    var gaugeMaxValue = arg3;

    var percentValue = value / gaugeMaxValue;
    var needleClient;
    (function () {
        var barWidth, chart, chartInset, degToRad, repaintGauge, height, margin, numSections, padRad, percToDeg, percToRad, percent, radius, sectionIndx, svg, totalPercent, width, recalcPointerPos;

        percent = percentValue;

        numSections = 1;
        sectionPerc = 1 / numSections / 2;
        padRad = 0.025;
        chartInset = 10;

        // Orientation of Gauge:
        totalPercent = .75;

        el = d3.select("#" + arg2);

        margin = {
            top: 20,
            right: 140,
            bottom: 20,
            left: 10
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

        chart.append('path').attr('class', "arc chart-first");
        chart.append('path').attr('class', "arc chart-second");
        chart.append('path').attr('class', "arc chart-third");

        arc3 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)
        arc2 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)
        arc1 = d3.svg.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth)

        repaintGauge = function () {
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

            chart.select(".chart-first").attr('d', arc1);
            chart.select(".chart-second").attr('d', arc2);
            chart.select(".chart-third").attr('d', arc3);
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

            function Needle(el) {
                this.el = el;
                this.len = width / 2.5;
                this.radius = this.len / 8;
            }

            Needle.prototype.render = function () {
                this.el.append('circle').attr('class', 'needle-center').attr('cx', 0).attr('cy', 0).attr('r', this.radius);
                return this.el.append('path').attr('class', 'needle').attr('id', 'client-needle').attr('d', recalcPointerPos.call(this, 0));
            };

            Needle.prototype.moveTo = function (perc) {
                var self,
                    oldValue = this.perc || 0;
                this.perc = perc;
                self = this;

                // Reset pointer position
                this.el.transition().delay(100).ease('quad').duration(200).select('.needle').tween('reset-progress', function () {
                    return function (percentOfPercent) {
                        var progress = (1 - percentOfPercent) * oldValue;
                        repaintGauge(progress);
                        return d3.select(this).attr('d', recalcPointerPos.call(self, progress));
                    };
                });

                this.el.transition().delay(300).ease('bounce').duration(1500).select('.needle').tween('progress', function () {
                    return function (percentOfPercent) {
                        var progress = percentOfPercent * perc;

                        repaintGauge(progress);
                        return d3.select(this).attr('d', recalcPointerPos.call(self, progress));
                    };
                });

            };

            return Needle;

        })();

        var dataset = [{
            metric: name,
            value: value
        }]

        var texts = svg.selectAll("text")
            .data(dataset)
            .enter();

        // var trX = 100 - 100 * Math.cos(percToRad(percent / 2));
        // var trY = 11 + Math.abs(100 * Math.cos(percToRad(percent / 2)));
		
		var trX = 80 - 80 * Math.cos(percToRad(percent / 2)) - 10 * Math.sin(percToRad(percent / 2))  - Math.cos(percToRad(percent / 2)) * Math.cos(percToRad(percent / 2)) * 10;
		var trY = 70 - 60 * Math.sin(percToRad(percent / 2));
		// for(var i = 0; i < 100 ; i++) {
		// 	var tempX = 80 - 80 * Math.cos(percToRad(i / 200)) - 10 * Math.sin(percToRad(i / 2))  - Math.cos(percToRad(i / 2)) * Math.cos(percToRad(i / 2)) * 10;
		// 	var tempY = 70 - 60 * Math.sin(percToRad(i / 200));
		// 	texts.append("text").text("29.44M").attr('transform', "translate(" + (tempX) + ", " + tempY + ")")
        //         .attr("font-size", 9);
		// 	console.log("" +tempX + " " + tempY);
		// }
        displayValue = function () {
            texts.append("text")
                .text(function () {
                    return "".concat(nFormatter(dataset[0].value, 3));
                })
                .attr('id', "Value")
                .attr('transform', "translate(" + (trX) + ", " + trY + ")")
                .attr("font-size", 9)
                .style("fill", '#000000');
			texts.append("text")
                .text(function () {
                    return "Peak value: ".concat(nFormatter(arg6, 3));
                })
                .attr('id', "Value")
                .attr('transform', "translate(" + (40) + ", " + 105 + ")")
                .attr("font-size", 9)
                .style("fill", '#000000');
        }

        texts.append("text")
            .text(function () {
                return 0;
            })
            .attr('id', 'scale0')
            .attr('transform', "translate(" + ((width + margin.left) / 100  + 5) + ", " + ((height + margin.top) / 2 + 10) + ")")
            .attr("font-size", 10)
            .style("fill", "#000000");



        texts.append("text")
            .text(function () {
                return nFormatter(gaugeMaxValue, 3);
            })
            .attr('id', 'scale20')
            .attr('transform', "translate(" + ((width + margin.left) / 1.03 - 15 ) + ", " + ((height + margin.top) / 2 + 10) + ")")
            .attr("font-size", 10)
            .style("fill", "#000000");

        needle = new Needle(chart);
        needle.render();
        needle.moveTo(percent);

        setTimeout(displayValue, 1350);

    })();
}
</script>	
<script src="../../system/smonitor/d3.v4.min.js"></script>
