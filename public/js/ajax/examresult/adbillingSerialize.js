toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var year = urlParams.get('year') || ''; 

    var dataTable = $('#adbilltable').DataTable({
        "ajax": {
            "url": adbillReadRoute,
            "type": "GET",
            "data": { 
                "year": year,
            },
            "dataSrc": function(json) {
                var no = 1;
                return json.data.map(function(adbildata) {  // Accessing the array inside 'data'
                    return {
                        no: no++,
                        lname: adbildata.lname,
                        fname: adbildata.fname,
                        mname: adbildata.mname ? adbildata.mname.charAt(0) : '',
                        gender: adbildata.gender,
                        bday: moment(adbildata.bday).format('MM/DD/YYYY'),
                        progAcronym: adbildata.progAcronym ? adbildata.progAcronym : 'N/A', 
                        contact: adbildata.contact,
                        percentile: adbildata.percentile || 'N/A'
                    };
                });
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            { data: 'no' },
            { data: 'lname' },
            { data: 'fname' },
            { data: 'mname' },
            { data: 'gender' },
            { data: 'bday' },
            { data: 'progAcronym' },
            { data: null, render: function() { return 1; } },
            { data: 'contact' },
            { data: null, render: function() { return 250; } },
            { data: 'percentile' },
        ],
        dom: 'Bfrtip'
    });
    $(document).on('billAdded', function() {
        dataTable.ajax.reload();
    }).buttons().container().appendTo('#adbilltable_wrapper .col-md-6:eq(0)');
});