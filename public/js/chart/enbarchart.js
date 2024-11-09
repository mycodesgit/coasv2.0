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
            label: 'No. of Students Enrolled in ' + previousSemesterLabel + ' (' + previousSchlyearYear + ')',
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
    var currentSemesterLabel = prevsemesteractive; // Store the semester label for current year

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
            label: 'No. of Students Enrolled in ' + currentSemesterLabel + ' (' + schlyearActive + ')',
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
    // Canvas for the previous and current semester charts
    var prevSemesterChartCanvas = $('#prevSemesterBarChart').get(0).getContext('2d');
    var currSemesterChartCanvas = $('#currSemesterBarChart').get(0).getContext('2d');

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