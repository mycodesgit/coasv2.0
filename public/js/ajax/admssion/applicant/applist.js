$(document).ready(function () {
    $('#example').DataTable({
        "ajax": {
            "url": allApplicantRoute,
            "type": "GET",
            "data": function (d) {
                d.year = $('#year').val();
                d.campus = $('#campus').val();
            },
            "dataSrc": "data",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false },
            { data: 'admission_id', name: 'admission_id' },
            { data: 'fname', name: 'fname' },
            { data: 'type', name: 'type' },
            { data: 'contact', name: 'contact' },
            { data: 'created_at', name: 'created_at',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY');
                    } else {
                        return data;
                    }
                }
            },
            { data: 'campus', name: 'campus' },
            // Add more columns as needed
        ],
        initComplete: function (settings, json) {
            var api = this.api();
            api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        },
        createdRow: function (row, data, dataIndex) {
            $(row).attr('id', 'tr-' + data.id);
        }
    });
});


