$(function () {
    var ticksStyle = {
        color: '#495057',
        font: {
            weight: 'bold'
        }
    };

    var mode = 'index';
    var intersect = true;

    var $salesChart = $('#sales-chart');
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
            labels: ['Main', 'Ilog', 'Cauayan', 'Candoni', 'Sipalay', 'Hinobaan', 'Hinigaran', 'Moises', 'San Carlos', 'Victorias'],
            datasets: [
                {
                    backgroundColor: '#90ee90',
                    borderColor: '#ced4da',
                    borderRadius: 8, // <--- Curved edges
                    borderSkipped: false, // <--- Curves all corners
                    data: [
                        $salesChart.data('main'),
                        $salesChart.data('ilog'),
                        $salesChart.data('cauayan'),
                        $salesChart.data('candoni'),
                        $salesChart.data('siplay'),
                        $salesChart.data('hinobaan'),
                        $salesChart.data('hinigaran'),
                        $salesChart.data('moises'),
                        $salesChart.data('sancarlos'),
                        $salesChart.data('victorias'),
                    ],
                },
                {
                    backgroundColor: '#00a65a',
                    borderColor: '#ced4da',
                    borderRadius: 8, // <--- Curved edges
                    borderSkipped: false,
                    data: [
                        $salesChart.data('main-sched'),
                        $salesChart.data('ilog-sched'),
                        $salesChart.data('cauayan-sched'),
                        $salesChart.data('candoni-sched'),
                        $salesChart.data('siplay-sched'),
                        $salesChart.data('hinobaan-sched'),
                        $salesChart.data('hinigaran-sched'),
                        $salesChart.data('moises-sched'),
                        $salesChart.data('sancarlos-sched'),
                        $salesChart.data('victorias-sched'),
                    ],
                },
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: mode,
                    intersect: intersect,
                    callbacks: {
                        title: function (tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function (tooltipItem) {
                            return 'Count: ' + tooltipItem.formattedValue;
                        }
                    }
                }
            },
            interaction: {
                mode: mode,
                intersect: intersect
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: true
                    },
                    ticks: ticksStyle
                }
            }
        }
    });
});