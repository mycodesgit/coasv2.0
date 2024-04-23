$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true, 
        "autoWidth": true,
        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $("#studhis").DataTable({
        "responsive": true,
        "lengthChange": false, 
        "autoWidth": true,
        "searching": false,
        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

    }).buttons().container().appendTo('#studhis_wrapper .col-md-6:eq(0)');

    $("#studgradeid").DataTable({
        "responsive": false,
        "lengthChange": true, 
        "autoWidth": true,
        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

    }).buttons().container().appendTo('#studgradeid_wrapper .col-md-6:eq(0)');

    $("#strand").DataTable({
        "responsive": false,
        "lengthChange": true, 
        "autoWidth": true,

    }).buttons().container().appendTo('#strand_wrapper .col-md-6:eq(0)');

    $("#adDate").DataTable({
        "responsive": false,
        "lengthChange": true, 
        "autoWidth": true,

    }).buttons().container().appendTo('#adDate_wrapper .col-md-6:eq(0)');

    $("#adTime").DataTable({
        "responsive": false,
        "lengthChange": true, 
        "autoWidth": true,

    }).buttons().container().appendTo('#adTime_wrapper .col-md-6:eq(0)');

    $("#adVenue").DataTable({
        "responsive": false,
        "lengthChange": true, 
        "autoWidth": true,

    }).buttons().container().appendTo('#adVenue_wrapper .col-md-6:eq(0)');


    $("#example3").DataTable({
        "responsive": true,
        "lengthChange": true, 
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

    $("#report").DataTable({
        "responsive": true,
        "lengthChange": false, 
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "colvis"]

    }).buttons().container().appendTo('#report_wrapper .col-md-6:eq(0)');

    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        height: '100'
    })
});