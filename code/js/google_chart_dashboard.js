/**
 * Created by yuanyuxuan on 27/1/16.
 */
/**
 * Created by yuanyuxuan on 25/1/16.
 */
google.charts.load('current', {
    'packages': ['corechart', 'gauge']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data3 = google.visualization.arrayToDataTable([
        ['Project', '#Task', '#Issue'],
        ['P1', 20, 3],
        ['P2', 15, 14],
        ['P3', 40, 73],
        ['P4', 30, 47],
        ['P5', 14, 11],
        ['P6', 15, 14],
        ['P7', 40, 73],
        ['P8', 30, 47],
        ['P9', 14, 100]
    ]);



    var data5 = new google.visualization.DataTable();
    data5.addColumn('string', 'Phase');
    data5.addColumn('number', 'time_spent');
    data5.addRows([
        ['Lead', 334],
        ['Requirement', 142],
        ['Build', 1623],
        ['Testing', 553],
        ['Deploy', 314]
    ]);


    var options3 = {
        title: 'Task/Issue Analysis',
        'legend':'bottom',
        //legend:'bottom',
        chartArea: { width: '90%',left: "5%" , height: '70%'},
        vAxis: {
        },
        hAxis: {
        },
        seriesType: 'bars',
        colors: ['#1b9e77', '#d95f02']

    };



    // Set chart options
    var options5 = {
        'title': 'Phase percentile analysis',
        'legend':'right',
        chartArea: { width: '100%',left: "15%",height: "80%" },
    };
    // Instantiate and draw our chart, passing in some options.



    var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
    chart3.draw(data3, options3);




    var chart5 = new google.visualization.PieChart(document.getElementById('chart_div5'));
    chart5.draw(data5, options5);


}
