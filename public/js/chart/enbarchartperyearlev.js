$(function () {
    // Canvas for the previous and current semester charts
    var prevSemesterChartCanvas = $('#prevSemesterBarChart').get(0).getContext('2d');
    //var currSemesterChartCanvas = $('#currSemesterBarChart').get(0).getContext('2d');

    // Define year levels
    var yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    prevSemesterLabel = prevsemesteractive == 1 ? '1st Sem' : (prevsemesteractive == 2 ? '2nd Sem' : (prevsemesteractive == 3 ? 'Summer' : ''));
    currSemesterLabel = semesteractive == 1 ? '1st Sem' : (semesteractive == 2 ? '2nd Sem' : (semesteractive == 3 ? 'Summer' : ''));
    
    // Chart data for the previous semester
    var prevSemesterData = {
        labels: yearLevels,
        datasets: [{
            label: 'No. of Students Enrolled in ' + prevSemesterLabel + ' (' + previousSchlyearYear + ')',
            data: prevenrolmentCounts,
            backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f']
        }]
    };

    var prevSemesterOptions = {
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: { beginAtZero: true }
            }]
        }
    };

    // Initialize chart for the previous semester
    new Chart(prevSemesterChartCanvas, {
        type: 'bar',
        data: prevSemesterData,
        options: prevSemesterOptions
    });

    // Chart data for the current semester
    var currSemesterData = {
        labels: yearLevels,
        datasets: [{
            label: 'No. of Students Enrolled in ' + currSemesterLabel + ' (' + schlyearActive + ')',
            data: currenrolmentCounts,
            backgroundColor: ['#108d6d', '#e74c3c', '#2ecc71', '#f1c40f']
        }]
    };

    var currSemesterOptions = {
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: { beginAtZero: true }
            }]
        }
    };

    // Initialize chart for the current semester
    new Chart(currSemesterChartCanvas, {
        type: 'bar',
        data: currSemesterData,
        options: currSemesterOptions
    });
});

$(function () {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }
    var mode = 'index';
    var intersect = true;

    var $salesChart = $('#enrlmntpercamp-chart');
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
            labels: ['Main', 'Victorias', 'San Carlos', 'Hinigaran', 'Moises Padilla', 'Ilog', 'Candoni', 'Cauayan', 'Sipalay', 'Hinobaan'],
            datasets: [
                {
                    backgroundColor: '#00a65a',
                    borderColor: '#ced4da',
                    data: [
                        $salesChart.data('main'),
                        $salesChart.data('victorias'),
                        $salesChart.data('sancarlos'),
                        $salesChart.data('hinigaran'),
                        $salesChart.data('moises'),
                        $salesChart.data('ilog'),
                        $salesChart.data('candoni'),
                        $salesChart.data('cauayan'),
                        $salesChart.data('siplay'),
                        $salesChart.data('hinobaan'),
                    ],
                },
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect,
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data.labels[tooltipItem[0].index];
                    },
                    label: function (tooltipItem, data) {
                        return 'Count: ' + tooltipItem.value;
                    }
                }
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: true
                    },
                    ticks: ticksStyle
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: true
                    },
                    ticks: {
                        display: false
                    }
                }],
            }
        }
    });
});