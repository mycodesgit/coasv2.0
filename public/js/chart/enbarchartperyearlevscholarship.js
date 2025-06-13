$(function () {
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');

    var pieData = {
        labels: scholarLabels, // dynamically from PHP
        datasets: [
            {
                data: scholarCounts, // dynamically from PHP
                backgroundColor: [
                    '#ffc107', '#00a65a', '#90ee90', '#007bff', '#ff6384',
                    '#36a2eb', '#cc65fe', '#ffce56', '#e83e8c', '#20c997'
                ] // Add more if needed
            }
        ]
    };

    var pieOptions = {
        legend: {
            display: true
        }
    };

    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });
});
