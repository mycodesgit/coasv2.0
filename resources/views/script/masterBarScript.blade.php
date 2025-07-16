<script>
    $(function () {
        var ctx = $('#currentSemesterBarChart').get(0).getContext('2d');

        var groupedData = groupedCollegeData;
        var labels = Object.keys(groupedData); // college_abbrs
        var studYearLabels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        // Prepare empty arrays for each year
        var yearCounts = {
            1: [],
            2: [],
            3: [],
            4: []
        };

        // Store colors (same color for all bars in a group)
        var backgroundColors = [];

        labels.forEach(college => {
            let yearData = groupedData[college];

            // Initialize counts for 1st to 4th year
            let yearMap = { 1: 0, 2: 0, 3: 0, 4: 0 };

            yearData.forEach(item => {
                yearMap[item.studYear] = item.student_count;
            });

            // Push the counts
            yearCounts[1].push(yearMap[1]);
            yearCounts[2].push(yearMap[2]);
            yearCounts[3].push(yearMap[3]);
            yearCounts[4].push(yearMap[4]);

            // Save color from one of the entries
            backgroundColors.push(yearData[0]?.colcolor || '#ccc');
        });

        // Build datasets for each year
        var datasets = [1, 2, 3, 4].map(year => ({
            label: studYearLabels[year - 1],
            data: yearCounts[year],
            backgroundColor: year === 1 ? '#4e73df' :
                            year === 2 ? '#1cc88a' :
                            year === 3 ? '#36b9cc' :
                                        '#f6c23e'
        }));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // college_abbrs
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>