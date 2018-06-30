<script>
    window.onload = function () {

        let chart2 = new CanvasJS.Chart("expensesPerYearChart", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light5",
            title: {
                text: "Skumulowane wartości wybranych typów wydatków w latach."
            },
            legend: {
                cursor: "pointer",
                verticalAlign: "center",
                horizontalAlign: "right",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "column",
                name: "",
                indexLabel: "{y}",
                yValueFormatString: "#.## zł",
                showInLegend: false,
                dataPoints: <?php echo json_encode($expensesPerYear, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart2.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chart2.render();
        }

        let chart = new CanvasJS.Chart("expensesSharesChart", {
            animationEnabled: true,
            exportEnabled: true,
            title: {
                text: "Udział wartościowy typów wydatków w wybranym czasie."
            },
            subtitles: [{
                text: "Wartość w zł"
            }],
            data: [{
                type: "pie",
                showInLegend: false,
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - #percent%",
                yValueFormatString: "#.## zł",
                dataPoints: <?php echo json_encode($expensesShares, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        let chart3 = new CanvasJS.Chart("expensesPerMontChart", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1",
            title: {
                text: "Skumulowane wartości wybranych typów wydatków w miesiącach."
            },
            axisX: {
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            axisY: {
                title: "Wartość w zł",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            toolTip: {
                enabled: true
            },
            data: [{
                type: "area",
                yValueFormatString: "#.## zł",
                dataPoints: <?php echo json_encode($expensesPerMonth, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart3.render();


    };
</script>