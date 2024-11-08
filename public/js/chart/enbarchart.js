$(function () {
    // First Semester Bar Chart (Previous Year)
    var firstSemesterBarChartCanvas = $('#firstSemesterBarChart').get(0).getContext('2d');
    var firstSemesterCollegesData = collbar1Route;
    var firstSemesterCollegeAbbrs = [];
    var firstSemesterCollegeCounts = [];
    var firstSemesterCollegeColors = [];
    var previousSemesterLabel = semesteractive; // Store the semester label for previous year

    // Extract data for the first semester
    firstSemesterCollegesData.forEach(function(college) {
        firstSemesterCollegeAbbrs.push(college.college_abbr);
        firstSemesterCollegeCounts.push(college.college_count);
        firstSemesterCollegeColors.push(college.colcolor);
        previousSemesterLabel = college.semester == 1 ? '1st Sem' : (college.semester == 2 ? '2nd Sem' : (college.semester == 3 ? 'Summer' : ''));
    });

    var firstSemesterBarData = {
        labels: firstSemesterCollegeAbbrs,
        datasets: [{
            label: 'No. of Students in ' + previousSemesterLabel + ' (' + previousSchlyearYear + ')',
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

    // Second Semester Bar Chart (Current Year)
    var secondSemesterBarChartCanvas = $('#secondSemesterBarChart').get(0).getContext('2d');
    var secondSemesterCollegesData = collbar2Route;
    var secondSemesterCollegeAbbrs = [];
    var secondSemesterCollegeCounts = [];
    var secondSemesterCollegeColors = [];
    var currentSemesterLabel = semesteractive1; // Store the semester label for current year

    // Extract data for the second semester
    secondSemesterCollegesData.forEach(function(college) {
        secondSemesterCollegeAbbrs.push(college.college_abbr);
        secondSemesterCollegeCounts.push(college.college_count);
        secondSemesterCollegeColors.push(college.colcolor);
        currentSemesterLabel = college.semester == 1 ? '1st Sem' : (college.semester == 2 ? '2nd Sem' : (college.semester == 3 ? 'Summer' : ''));
    });

    var secondSemesterBarData = {
        labels: secondSemesterCollegeAbbrs,
        datasets: [{
            label: 'No. of Students in ' + currentSemesterLabel + ' (' + schlyearActive + ')',
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