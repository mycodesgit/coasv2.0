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
            backgroundColor: firstSemesterCollegeColors,
            borderRadius: 8,
    borderSkipped: 'bottom'
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

