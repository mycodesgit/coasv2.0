$(function () {
    var currSemesterUnderProgramScholarChartCanvas = $('#currSemesterunderprogScholarBarChart').get(0).getContext('2d');
    currSemesterLabel = semesteractive == 1 ? '1st Sem' : (semesteractive == 2 ? '2nd Sem' : (semesteractive == 3 ? 'Summer' : ''));

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    var barColors = undercolors.map(() => getRandomColor());

    var currSemesterGradData = {
        labels: underprogramScholar,
        datasets: [{
            label: '', // No label on the chart
            data: currunderprogramenrolmentScholarCounts,
            backgroundColor: barColors
        }]
    };

    // Add custom tooltip label
    var currSemesterOptions = {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        // Custom label on hover
                        return 'No. of Students Enrolled in ' + currSemesterLabel + ' (' + schlyearActive + '): ' + context.parsed.y;
                    }
                }
            },
            legend: {
                display: false // Hide legend since label is empty
            }
        }
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
    new Chart(currSemesterUnderProgramScholarChartCanvas, {
        type: 'bar',
        data: currSemesterGradData,
        options: currSemesterOptions
    });
});