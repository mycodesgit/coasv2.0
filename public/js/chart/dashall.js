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
                    backgroundColor: '#90ee90',
                    borderColor: '#ced4da',
                    data: [
                        $salesChart.data('main'),
                        $salesChart.data('ilog'),
                        $salesChart.data('cauayan'),
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
                    data: [
                        $salesChart.data('main-sched'),
                        $salesChart.data('ilog-sched'),
                        $salesChart.data('cauayan-sched'),
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
                // yAxes: [{
                //     display: true,
                //     gridLines: {
                //         display: true,
                //         lineWidth: '4px',
                //         color: 'rgba(0, 0, 0, .2)',
                //         zeroLineColor: 'transparent'
                //     },
                //     ticks: $.extend({
                //         beginAtZero: false,
                //         min: 1,
                //         // stepSize: 50 
                //     }, ticksStyle)
                // }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: true
                    },
                    ticks: ticksStyle
                }]
            }
        }
    });
});