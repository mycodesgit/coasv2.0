toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var datesearch = urlParams.get('datesearch') || '';

    var dataTable = $('#orcollectiontable').DataTable({
        "ajax": {
            "url": orCollectionReadRoute,
            "type": "GET",
            "data": {
                "datesearch": datesearch,
            }
        },
        info: false,
        responsive: false,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel'
            ],
        "columns": [
            {
                data: 'datepaid',
                render: function(data, type) {
                    if (type === 'display') {
                        return moment(data).format('MMM D, YYYY');
                    }

                    return data;
                }
            },

            { data: 'orno' },

            {
                data: null,
                render: function(data) {
                    var firstname = data.fname;
                    var middleInitial = data.mname
                        ? data.mname.substr(0, 1) + '.'
                        : '';

                    var lastName = data.lname;

                    return lastName + ', ' + firstname + ' ' + middleInitial;
                }
            },

            {
                data: null,
                render: function(data) {
                    return data.semester + ' - ' + data.schlyear;
                }
            },

            { data: 'studID' },

            // Certification
            {
                data: 'certification',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Tuition
            {
                data: 'tuition',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Admission
            {
                data: 'admission',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Athletics
            {
                data: 'athletics',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Computer Lab
            {
                data: 'computer_lab',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Cultural
            {
                data: 'cultural',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Developmental
            {
                data: 'developmental',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Guidance
            {
                data: 'guidance',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Laboratory
            {
                data: 'laboratory',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Library
            {
                data: 'library',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Medical
            {
                data: 'medical',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Registration
            {
                data: 'registration',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },

            // Student ID Card
            {
                data: 'stud_id_card',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },
            // Honorable Dismissal
            {
                data: 'honorable',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },
            // Yearbook
            {
                data: 'yearbook',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },
            // Total
            {
                data: 'total',
                render: function(data) {
                    return parseFloat(data || 0).toFixed(2);
                }
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id);
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#orcollectiontable_wrapper .col-md-6:eq(0)');
    $(document).on('studOrAdded', function() {
        dataTable.ajax.reload();
    });
});
