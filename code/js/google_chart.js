/**
 * Created by yuanyuxuan on 25/1/16.
 */
google.charts.load('current', {
    'packages': ['corechart', 'gauge']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['Urgency', 50]
    ]);
// Urgency = SUM(pending_issue_priority/days_left)*project_priority
//Range to be set by admin panel

    var data2 = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['Importancy', 30]
    ]);
//5 Level of priority: {1,2,3,4,5} --> {10, 30, 50, 70, 90}

    var data3 = google.visualization.arrayToDataTable([
        ['Phase', '#Task', '#Issue', 'Metrics'],
        ['Lead', 20, 3, 1],
        ['Requirement', 15, 14, 0.7],
        ['Build', 40, 73, 1.5],
        ['Testing', 30, 47, 0.6],
        ['Deployment', 14, 11, 1.1]
    ]);


    // Get JSON table

    var jsonData4 = $.ajax({
        url: "getData.php",
        dataType: "json",
        async: false
    }).responseText;



    var data4 = new google.visualization.DataTable();
    data4.addColumn('string', 'issue#');
    data4.addColumn('number', 'schedule metric');
// A column for custom tooltip content
    data4.addColumn({
        type: 'string',
        role: 'tooltip'
    });
//tooltip will show , issue titile, start date, the days planned, days actually spent, metric
    data4.addRows([
        ['1', 0.8, 'tooltip pending'],
        ['3', 1.6, 'tooltip pending'],
        ['4', 0.5, 'tooltip pending'],
        ['5', 0.8, 'tooltip pending'],
		['11', 0.8, 'tooltip pending'],
        ['13', 1.6, 'tooltip pending'],
        ['14', 0.5, 'tooltip pending'],
        ['15', 0.8, 'tooltip pending'],
        ['17', 1.5, 'tooltip pending'],
        ['23', 1.6, 'tooltip pending'],
        ['24', 0.5, 'tooltip pending'],
        ['25', 0.8, 'tooltip pending'],
		['31', 0.8, 'tooltip pending'],
        ['33', 1.6, 'tooltip pending'],
        ['34', 0.5, 'tooltip pending'],
        ['35', 0.8, 'tooltip pending'],
        ['37', 1.5, 'tooltip pending']
    ]);



    var data5 = new google.visualization.DataTable();
    data5.addColumn('string', 'Stage');
    data5.addColumn('number', 'time_spent');
    data5.addRows([
        ['to develop', 3],
        ['to test', 1],
        ['ready for deploy', 1],
        ['to deploy', 1]
    ]);




    var options = {
        width: 500,
        height: 120,
        redFrom: 80,
        redTo: 100,
        yellowFrom: 60,
        yellowTo: 80,
        minorTicks: 5
    };

    var options2 = {
        width: 100,
        height: 120,
        redFrom: 80,
        redTo: 100,
        yellowFrom: 60,
        yellowTo: 80,
        minorTicks: 5
    };

    var options3 = {
        title: 'Phase Analysis Chart',
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



    var options4 = {
        'title': 'Issue Metrics Chart',
		chartArea: { left: "5%"},
        //'height': 300,
        tooltip: {
            isHtml: true
        },
        legend: 'none',
        vAxis: {
            'max': 3
        }
    };



    // Set chart options
    var options5 = {
        'title': 'Stage percentile analysis',
		chartArea: { width: '100%',left: "15%",height: "80%" },
    };
    // Instantiate and draw our chart, passing in some options.



    var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

    chart.draw(data, options);


    var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));

    chart2.draw(data2, options2);

    var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
    chart3.draw(data3, options3);


    var chart4 = new google.visualization.LineChart(document.getElementById('chart_div4'));
    chart4.draw(data4, options4);

    var chart5 = new google.visualization.PieChart(document.getElementById('chart_div5'));
    chart5.draw(data5, options5);


}
