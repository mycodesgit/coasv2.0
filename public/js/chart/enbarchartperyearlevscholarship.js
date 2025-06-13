$(function () {
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');

    var pieData = {
        labels: scholarLabels, // dynamically from PHP
        datasets: [
            {
                // dynamically from PHP
                backgroundColor: [
                    '#ffc107', '#00a65a', '#90ee90', '#007bff', '#ff6384',
                    '#36a2eb', '#cc65fe', '#ffce56', '#e83e8c', '#20c997',
                    '#f94144', '#f3722c', '#f8961e', '#f9844a', '#f9c74f',
                    '#90be6d', '#43aa8b', '#577590', '#277da1', '#4d908e',
                    '#577590', '#b5179e', '#7209b7', '#560bad', '#480ca8',
                    '#3a0ca3', '#4361ee', '#4895ef', '#4cc9f0', '#00b4d8',
                    '#48cae4', '#90e0ef', '#ade8f4', '#caf0f8', '#ffb4a2',
                    '#e5989b', '#b5838d', '#6d6875', '#ff6f61', '#6b5b95',
                    '#feb236', '#d64161', '#ff7b25', '#c94c4c', '#ffcc5c',
                    '#88b04b', '#92a8d1', '#955251', '#b565a7', '#009b77',
                    '#dd4124', '#d65076', '#45b8ac', '#efc050', '#5b5ea6',
                    '#9b2335', '#dfcfbe', '#55b4b0', '#e15d44', '#7fcdcd',
                    '#bc243c', '#c3447a'
                ] // 60 colors total
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
