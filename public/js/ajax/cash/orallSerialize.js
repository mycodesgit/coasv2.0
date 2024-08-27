// toastr.options = {
//     "closeButton": true,
//     "progressBar": true,
//     "positionClass": "toast-top-right"
// };
// $(document).ready(function() {
//     var dataTable = $('#oralltable').DataTable({
//         "ajax": {
//             "url": studallorReadRoute,
//             "type": "GET",
//         },
//         info: false,
//         responsive: true,
//         lengthChange: false,
//         searching: false,
//         paging: false,
//         "columns": [
//             {data: 'orno'},
//             {data: 'studID'},
//         ],
//         "createdRow": function (row, data, index) {
//             $(row).attr('id', 'tr-' + data.id); 
//         }
//     });
//     $(document).on('studOrAdded', function() {
//         dataTable.ajax.reload();
//     });
// });