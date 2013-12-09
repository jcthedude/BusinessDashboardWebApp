function chartYear() {

    var visitors = [[0, 0.5], [1, 1], [2, 1.5], [3, 2],[4, 2.5], [5, 2], [6, 1.5], [7, 1.5],[8, 2], [9, 2.5], [10, 2.5], [11, 3],[12, 3], [13, 2.5], [14, 2.5], [15, 2],[16, 3], [17, 2.5], [18, 2], [19, 1.5],[20, 1], [21, 0.5], [22, 1], [23, 1],[24, 1.5], [25, 2], [26, 2.5], [27, 3],[28, 2.5], [29, 2], [30, 1.5], [31, 1]];
    var new_visits = [[0, 1], [1, 2], [2, 3], [3, 4],[4, 5], [5, 4], [6, 3], [7, 3],[8, 4], [9, 5], [10, 5], [11, 6],[12, 6], [13, 5], [14, 5], [15, 4],[16, 6], [17, 5], [18, 4], [19, 3],[20, 2], [21, 1], [22, 2], [23, 2],[24, 3], [25, 4], [26, 5], [27, 6],[28, 5], [29, 4], [30, 3], [31, 2]];

	var plot = $.plot($("#chart-year"),
		[ { data: visitors, label: "Visitors" },
		{ data: new_visits, label: "New Visits"} ], {
			series: {
				lines: { 
					show: true,
					lineWidth: 2,
					fill: true,
					fillColor: { colors: [ { opacity: 0.1 }, { opacity: 0.1 } ] } 
				},
				points: { 
					show: true, 
					lineWidth: 2 
				},
				shadowSize: 0
			},
			grid: { 
				hoverable: true, 
				clickable: true, 
				borderWidth: 0
			},
			legend: {
				show: true
			},	
			colors: ["#bdea74", "#2FABE9"],
			xaxis: {ticks:10, tickDecimals: 0, tickColor: "#fff"},
			yaxis: {ticks:5, tickDecimals: 0, tickColor: "#e9ebec"}
		});

	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#dfeffc',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#chart-year").bind("plothover", function (event, pos, item) {
		
		$("#x").text(pos.x.toFixed(2));
		$("#y").text(pos.y.toFixed(2));
		
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);

				showTooltip(item.pageX, item.pageY,item.series.label + " of " + x + " = " + y);
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
	});	
}

	
function chartMonth(){

    var visitors = [[0, 15], [1, 1], [2, 1.5], [3, 2],[4, 2.5], [5, 2], [6, 1.5], [7, 1.5],[8, 2], [9, 2.5], [10, 2.5], [11, 3],[12, 3], [13, 2.5], [14, 2.5], [15, 2],[16, 3], [17, 2.5], [18, 2], [19, 1.5],[20, 1], [21, 0.5], [22, 1], [23, 1],[24, 1.5], [25, 2], [26, 2.5], [27, 3],[28, 2.5], [29, 2], [30, 1.5], [31, 1]];
    var new_visits = [[0, 10], [1, 2], [2, 3], [3, 4],[4, 5], [5, 4], [6, 3], [7, 3],[8, 4], [9, 5], [10, 5], [11, 6],[12, 6], [13, 5], [14, 5], [15, 4],[16, 6], [17, 5], [18, 4], [19, 3],[20, 2], [21, 1], [22, 2], [23, 2],[24, 3], [25, 4], [26, 5], [27, 6],[28, 5], [29, 4], [30, 3], [31, 2]];

    var plot = $.plot($("#chart-month"),
        [ { data: visitors, label: "Visitors" },
            { data: new_visits, label: "New Visits"} ], {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: { colors: [ { opacity: 0.1 }, { opacity: 0.1 } ] }
                },
                points: {
                    show: true,
                    lineWidth: 2
                },
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                borderWidth: 0
            },
            legend: {
                show: true
            },
            colors: ["#bdea74", "#2FABE9"],
            xaxis: {ticks:10, tickDecimals: 0, tickColor: "#fff"},
            yaxis: {ticks:5, tickDecimals: 0, tickColor: "#e9ebec"}
        });

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#dfeffc',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#chart-month").bind("plothover", function (event, pos, item) {

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                showTooltip(item.pageX, item.pageY,item.series.label + " of " + x + " = " + y);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });

}


$(document).ready(function(){

    /* ---------- Init Main Chart ---------- */
    chartMonth();

    $('#chartYear').click(function(){
        chartYear();
    });


	/* ---------- Tabs ---------- */
	$('#mainCharts a:last').tab('show');
	$('#mainCharts a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});
	
});

