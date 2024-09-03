$(function () {
    // First Semester Bar Chart
    var firstSemesterBarChartCanvas = $('#firstSemesterBarChart').get(0).getContext('2d');
    var firstSemesterCollegesData = collbar1Route;
    var firstSemesterCollegeAbbrs = [];
    var firstSemesterCollegeCounts = [];
    var firstSemesterCollegeColors = [];
    
    // Extract data for the first semester
    firstSemesterCollegesData.forEach(function(college) {
        firstSemesterCollegeAbbrs.push(college.college_abbr);
        firstSemesterCollegeCounts.push(college.college_count);
        firstSemesterCollegeColors.push(college.colcolor);
    });

    var prevschlyear = collbarprevYearRoute;
    var nowschlyear = collbarnowYearRoute;

    var firstSemesterBarData = {
        labels: firstSemesterCollegeAbbrs,
        //labels: firstSemesterCollegeAbbrs.map((abbr, index) => abbr + '\n' + firstSemesterCollegeCounts[index]),
        datasets: [{
            label: 'No. of Students enrolled in 1st Sem ' + prevschlyear + '-' + nowschlyear,
            data: firstSemesterCollegeCounts,
            backgroundColor: firstSemesterCollegeColors
        }]
    };
    
    var firstSemesterBarOptions = {
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    };
    
    var firstSemesterBarChart = new Chart(firstSemesterBarChartCanvas, {
        type: 'bar',
        data: firstSemesterBarData,
        options: firstSemesterBarOptions
    });
    
    // Second Semester Bar Chart
    var secondSemesterBarChartCanvas = $('#secondSemesterBarChart').get(0).getContext('2d');
    var secondSemesterCollegesData = collbar2Route;
    var secondSemesterCollegeAbbrs = [];
    var secondSemesterCollegeCounts = [];
    var secondSemesterCollegeColors = [];
    
    // Extract data for the second semester
    secondSemesterCollegesData.forEach(function(college) {
        secondSemesterCollegeAbbrs.push(college.college_abbr);
        secondSemesterCollegeCounts.push(college.college_count);
        secondSemesterCollegeColors.push(college.colcolor);
    });

    var prevschlyear = collbarprevYearRoute;
    var nowschlyear = collbarnowYearRoute;
    
    var secondSemesterBarData = {
        labels: secondSemesterCollegeAbbrs,
        datasets: [{
            label: 'No. of Students enrolled in 2nd Sem ' + prevschlyear + '-' + nowschlyear,
            data: secondSemesterCollegeCounts,
            backgroundColor: secondSemesterCollegeColors
        }]
    };
    
    var secondSemesterBarOptions = {
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    };
    
    var secondSemesterBarChart = new Chart(secondSemesterBarChartCanvas, {
        type: 'bar',
        data: secondSemesterBarData,
        options: secondSemesterBarOptions
    });
});



$(function () {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index';
    var intersect = true;

    var $salesChart = $('#sales-chart');
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
            labels: ['Main', 'Ilog', 'Cauayan', 'Sipalay', 'Hinobaan', 'Hinigaran', 'Moises', 'San Carlos', 'Victorias'],
            datasets: [
                {
                    backgroundColor: '#00a65a',
                    borderColor: '#ced4da',
                    data: [
                        $salesChart.data('main'),
                        $salesChart.data('ilog-high'),
                        $salesChart.data('cauayan-high'),
                        $salesChart.data('siplay-high'),
                        $salesChart.data('hinobaan-high'),
                        $salesChart.data('hinigaran-high'),
                        $salesChart.data('moises-high'),
                        $salesChart.data('sancarlos-high'),
                        $salesChart.data('victorias-high'),
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