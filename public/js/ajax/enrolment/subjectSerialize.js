$(document).ready(function() {
    var dataTable = $('#listsub').DataTable({
        "ajax": {
            "url": subjectReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'sub_code'},
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'sub_unit'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('coaAdded', function() {
        dataTable.ajax.reload();
    });
});


$(document).ready(function() {
    console.log('Document is ready');

    $('#college, #department').change(function() {
        console.log('Change event triggered'); 

        var college = $('#college').val();
        var department = $('#department').val();

        console.log('College:', college, 'Department:', department);

        if (college && department) {
            $.ajax({
                url: subjectCodeRoute,
                type: 'GET',
                data: {
                    college_abbr: college,
                    deptCod: department
                },
                success: function(data) {
                    console.log('Received response:', data);
                    var combinedCode = college + '-' + department + '-' + data.nextNumber;
                    $('#sub_code').val(combinedCode);
                    $('#subjcostcenter').val(college + '-' + department);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error retrieving the next subject number');
                }
            });
        }
    });
});
