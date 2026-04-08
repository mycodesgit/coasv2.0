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
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "order": [[0, 'desc']]

    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

    $("#example3studrecord").DataTable({
        "responsive": true,
        "lengthChange": false, 
        "autoWidth": false,
        "paging": false,
        "searching": false,
        "buttons": ["excel"]

    }).buttons().container().appendTo('#example3studrecord_wrapper .col-md-6:eq(0)');

    $("#report").DataTable({
        "responsive": true,
        "lengthChange": false, 
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "colvis"]

    }).buttons().container().appendTo('#report_wrapper .col-md-6:eq(0)');

    $("#hebilling").DataTable({
        "processing": true,
        "serverSide": false,  // Set to true if using AJAX for large datasets
        "paging": true,
        "searching": true,
        "buttons": ["copy", "excel","colvis"]

    }).buttons().container().appendTo('#hebilling_wrapper .col-md-6:eq(0)');

    // $('.select2').select2({
    //     dropdownParent: $('#editCollegeModal'),
    //     dropdownParent: $('#modal-addSub')
    // });

    $('.select2').each(function () {
        $(this).select2({
            dropdownParent: $(this).closest('.modal'),
        });
    });

    $('.select2bs4').select2({
        theme: 'bootstrap4',
        height: '100',
    })
});

document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll('.card-animate');

    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('show');
        }, index * 90); // stagger effect
    });
});