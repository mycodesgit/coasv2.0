$(document).ready(function() {
    var dataTable = $('#studhisTable').DataTable({
        "ajax": {
            "url": historyReadRoute,
            "type": "GET",
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'stud_id'},
            {data: 'lname'},
            {data: 'fname'},
            {data: 'mname'},
            {data: 'ext'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
        // Handle search type change event
        $('#searchType').change(function() {
            // Hide both input fields initially
            $('#searchLastName, #searchStudentID').hide();

            // Show the input field based on the selected search type
            var selectedOption = $(this).val();
            if (selectedOption === 'lname') {
                $('#searchLastName').show();
            } else if (selectedOption === 'studentID') {
                $('#searchStudentID').show();
            }
        });

        // Handle search button click event
        $('#searchButton').click(function() {
            var searchType = $('#searchType').val();
            var query = $('#' + searchType).val();

            // Make AJAX request to fetch data based on search type and query
            dataTable.ajax.url(historyReadRoute + '?type=' + searchType + '&' + searchType + '=' + query).load();
        });
});