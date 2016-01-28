/**
 * Created by yuanyuxuan on 28/1/16.
 */
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Week', 'TUS'],
        ['1',  1000],
        ['2',  1170],
        ['3',  660],
        ['4',  1030],
        ['5',  1170],
        ['6',  660],
        ['7',  1030],
        ['8',  242],
        ['9',  1170],
        ['10',  660],
        ['11',  1030],
        ['12',  24],
        ['13',  660],
        ['14',  2361]
    ]);


    var data3 = google.visualization.arrayToDataTable([
        ['Project', '#Task', '#Issue', 'Avg Issue Metrics'],
        ['P1', 20, 3, 1],
        ['P2', 15, 14, 0.7],
        ['P3', 40, 73, 1.5],
        ['P4', 30, 47, 0.6],
        ['P5', 14, 11, 1.1],
        ['P6', 40, 73, 1.5],
        ['P7', 30, 47, 0.6],
        ['P8', 14, 11, 1.1],
        ['P9', 40, 73, 1.5],
        ['P10', 30, 47, 0.6],
        ['P11', 14, 11, 1.1]
    ]);



    var options = {
        title: 'Total Urgency Score',
        legend: 'none',
        chartArea: {'width': '78%', 'height': '75%'},
        width: '100%',
        hAxis: {title: 'Week',  titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0}
    };

    var options3 = {
        title: 'Issue/Task Analysis Chart',
        //legend:'bottom',
        chartArea: { width: '90%',left: "5%" , height: '70%'},
        vAxis: {
        },
        hAxis: {
        },
        seriesType: 'bars',
        series: {
            0: {
                type: "bars",
                targetAxisIndex: 0
            },
            2: {
                type: "line",
                targetAxisIndex: 1
            }
        },
        colors: ['#1b9e77', '#d95f02', '#7570b3']

    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);


    var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
    chart3.draw(data3, options3);
}