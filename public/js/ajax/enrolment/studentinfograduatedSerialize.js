$(document).ready(function() {

    var urlParams = new URLSearchParams(window.location.search);
    var campus = urlParams.get('campus') || '';

    var dataTable = $('#studinfoallgrad').DataTable({
        "ajax": {
            "url": gradstudentlistinfoRoute,
            "type": "GET",
            "data": { 
                "campus": campus,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastName = data.lname;
                    var ext = data.ext !== 'N/A' ? ' ' + data.ext : '';
                    
                    return lastName + ', ' + firstname + ' ' + middleInitial + ext;
                }
            },
            {data: 'stud_id'},
            {data: 'gender'},
            {data: 'civil_status'},
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var editLink = '<a href="#" class="btn btn-primary btn-sm btn-studdataview"  data-id="' + row.id + '" data-fname="' + row.fname + '" data-lname="' + row.lname + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('studlistTable', function() {
        dataTable.ajax.reload();
    });
});

