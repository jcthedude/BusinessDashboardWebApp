function chartYear() {

    $.getJSON('modules/ga_chart_yearly.php', function(data_yearly) {

        $.plot($("#chart-year"),
            [ { data: data_yearly["visitors"], label: "Visitors" },
                { data: data_yearly["new_visits"], label: "New Visits"} ], {
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
                xaxis: {mode: "time", timeformat: "%m/%y", ticks:10, tickDecimals: 0, tickColor: "#fff"},
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
    });
}

	
function chartMonth(){

    $.getJSON('modules/ga_chart_monthly.php', function(data_monthly) {

        $.plot($("#chart-month"),
            [ { data: data_monthly["visitors"], label: "Visitors" },
                { data: data_monthly["new_visits"], label: "New Visits"} ], {
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
                xaxis: {mode: "time", timeformat: "%m/%d/%y", ticks: 10, tickDecimals: 0, tickColor: "#fff"},
                yaxis: {ticks: 5, tickDecimals: 0, tickColor: "#e9ebec"}
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

