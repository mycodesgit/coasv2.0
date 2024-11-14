$(function () {
    // Canvas for the previous and current semester charts
    var prevSemesterGradChartCanvas = $('#prevSemestergradBarChart').get(0).getContext('2d');
    var currSemesterGradChartCanvas = $('#currSemestergradBarChart').get(0).getContext('2d');
    var currSemesterGradProgramChartCanvas = $('#currSemestergradprogBarChart').get(0).getContext('2d');

    // Define year levels
    var yearLevels = ['New Students', 'Old Students',];
    prevSemesterLabel = prevsemesteractive == 1 ? '1st Sem' : (prevsemesteractive == 2 ? '2nd Sem' : (prevsemesteractive == 3 ? 'Summer' : ''));
    currSemesterLabel = semesteractive == 1 ? '1st Sem' : (semesteractive == 2 ? '2nd Sem' : (semesteractive == 3 ? 'Summer' : ''));
    
    // Chart data for the previous semester
    var prevSemesterGradData = {
        labels: yearLevels,
        datasets: [{
            label: 'No. of Students Enrolled in ' + prevSemesterLabel + ' (' + previousSchlyearYear + ')',
            data: prevgradenrolmentCounts,
            backgroundColor: ['#3498db', '#2ecc71']
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
    new Chart(prevSemesterGradChartCanvas, {
        type: 'bar',
        data: prevSemesterGradData,
        options: prevSemesterOptions
    });

    // Chart data for the current semester
    var currSemesterGradData = {
        labels: yearLevels,
        datasets: [{
            label: 'No. of Students Enrolled in ' + currSemesterLabel + ' (' + schlyearActive + ')',
            data: currgradenrolmentCounts,
            backgroundColor: ['#108d6d', '#2ecc71']
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
    new Chart(currSemesterGradChartCanvas, {
        type: 'bar',
        data: currSemesterGradData,
        options: currSemesterOptions
    });


    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    var barColors = colors.map(() => getRandomColor());

    var currSemesterGradData = {
        labels: programAcronyms,
        datasets: [{
            label: 'No. of Students Enrolled in ' + currSemesterLabel + ' (' + schlyearActive + ')',
            data: currgradProgenrolmentCounts,
            backgroundColor: barColors
        }]
    };

    var currSemesterOptions = {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Initialize chart for the current semester
    new Chart(currSemesterGradProgramChartCanvas, {
        type: 'bar',
        data: currSemesterGradData,
        options: currSemesterOptions
    });
});