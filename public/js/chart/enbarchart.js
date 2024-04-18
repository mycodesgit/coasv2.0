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