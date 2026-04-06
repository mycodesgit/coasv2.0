$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    function calculateAge(birthDate) {
        var today = new Date();
        var birth = new Date(birthDate);
        var age = today.getFullYear() - birth.getFullYear();
        var monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    var dataTable = $('#studstrandsTable').DataTable({
        "ajax": {
            "url": studStrandpersemRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus,
            }
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            {data: 'studentID'},
            {data: 'strand'},
            {
                data: 'bday',
                render: function(data, type, row) {
                    if (!data) return '';
                    var date = new Date(data);
                    var options = { year: 'numeric', month: 'short', day: '2-digit' };
                    return date.toLocaleDateString('en-US', options);
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return calculateAge(data.bday);
                }
            },
            {
                data: 'type',
                render: function(data, type, row) {
                    const typeMap = {1: 'Freshmen', 2: 'Returnee', 3: 'Transferee'};
                    return typeMap[data] || data;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname.toUpperCase();
                    var middleInitial = data.mname ? data.mname.substr(0, 1).toUpperCase() + '.' : '';
                    var lastName = data.lname.toUpperCase();
                    var extension = data.ext && data.ext !== 'N/A' ? data.ext : '';
                    return lastName + ', ' + firstname + ' ' + middleInitial + (extension ? ' ' + extension : '');
                }
            },
            {data: 'progName'},
            {data: 'progAcronym'},
            {data: 'studYear'},
            {data: 'studSec'},
            {data: 'schlyear'},
            {data: 'semester'},
            {data: 'address'},
            {data: 'brgy'},
            {data: 'city'},
            {data: 'province'},
            {data: 'region'},
            {data: 'zcode'},
            {
                data: null,
                render: function(data, type, row) {
                    return data.lstsch_attended && data.lstsch_attended.trim() !== ''
                        ? data.lstsch_attended
                        : (data.suc_lst_attended || '');
                }
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#courseEn_wrapper .col-md-6:eq(0)');
    $(document).on('studenrlld', function() {
        dataTable.ajax.reload();
    });
});