$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#courseEn').DataTable({
        "ajax": {
            "url": courseEnrollReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            { data: 'progCod' },
            { data: 'progName' },
            { data: 'progAcronym' },
            { 
                data: 'studYear',
                render: function(data, type, full, meta) {
                    return full.studYear + '-' + full.studSec;
                }
            },
            { 
                data: 'studentCount',
                render: function(data, type, full, meta) {
                    if (type === 'display') {
                        return '<strong>' + data + '</strong>';
                    }
                    return data;
                }
            },
            { data: 'maleCount' },
            { data: 'femaleCount' },
            {data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var link = '<a href="#" class="btn btn-primary btn-xs btn-viewstudent" data-program="' + row.progCod + '" data-year="' + row.studYear + '" data-section="' + row.studSec + '" data-schlyear="' + row.schlyear + '" data-semester="' + row.semester + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return link;
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
});

// $(document).ready(function() {
//     var urlParams = new URLSearchParams(window.location.search);
//     var schlyear = urlParams.get('schlyear') || ''; 
//     var semester = urlParams.get('semester') || '';
//     var campus = urlParams.get('campus') || ''; 

//     var dataTable = $('#courseEn').DataTable({
//         "ajax": {
//             "url": courseEnrollReadRoute,
//             "type": "GET",
//             "data": { 
//                 "schlyear": schlyear,
//                 "semester": semester,
//                 "campus": campus
//             }
//         },
//         info: true,
//         responsive: true,
//         lengthChange: true,
//         searching: true,
//         paging: true,
//         "columns": [
//             { data: 'progCode' },
//             { data: 'progName' },
//             { data: 'progAcronym' },
//             { 
//                 data: 'classSection',
//             },
//             { 
//                 data: 'studentCount',
//                 render: function(data, type, full, meta) {
//                     if (type === 'display') {
//                         return '<strong>' + data + '</strong>';
//                     }
//                     return data;
//                 }
//             },
//             { data: 'maleCount' },
//             { data: 'femaleCount' },
//             {data: 'id',
//                 render: function(data, type, row) {
//                     if (type === 'display') {
//                         var link = '<a href="#" class="btn btn-primary btn-xs btn-viewstudent" data-program="' + row.progCode + '" data-section="' + row.classSection + '" data-schlyear="' + row.schlyear + '" data-semester="' + row.semester + '">' +
//                             '<i class="fas fa-eye"></i>' +
//                             '</a>';
//                         return link;
//                     } else {
//                         return data;
//                     }
//                 },
//             },

//         ],
//         "createdRow": function (row, data, index) {
//             $(row).attr('id', 'tr-' + data.id); 
//         }
//     });
// });

$(document).ready(function() {
    $('#courseEn').on('click', '.btn-viewstudent', function() {
        var programCode = $(this).data('program');
        var studYear = $(this).data('year');
        var studSec = $(this).data('section');
        var schlyear = $(this).data('schlyear');
        var semester = $(this).data('semester');

        console.log('Program Code:', programCode);
        console.log('Class Year:', studYear);
        console.log('Class Section:', studSec);
        console.log('School Year:', schlyear);
        console.log('Semester:', semester);

        var pdfUrl = studentcourseEnrollPDFReadRoute + 
                     "?progCod=" + programCode + 
                     "&studYear=" + studYear + 
                     "&studSec=" + studSec + 
                     "&schlyear=" + schlyear + 
                     "&semester=" + semester;

        // var classSectionParts = classSection.split('-');
        // var studYear = classSectionParts[0];
        // var studSec = classSectionParts[1];

        $.ajax({
            url: studentcourseEnrollReadRoute,
            method: 'GET',
            data: { 
                progCod: programCode,
                studYear: studYear,
                studSec: studSec,
                schlyear: schlyear,
                semester: semester
            },
            success: function(response) {
                var studentEnrolledTable = $('#studentEnrolledTable');
                studentEnrolledTable.empty();

                if (response.data.length > 0) {
                    response.data.forEach(function(enroll) {
                        var semesterText;
                        switch(enroll.semester) {
                            case 1:
                                semesterText = '<span class="badge badge-info">1st Sem</span>';
                                break;
                            case 2:
                                semesterText = '<span class="badge badge-info">2nd Sem</span>';
                                break;
                            case 3:
                                semesterText = '<span class="badge badge-secondary">Summer</span>';
                                break;
                            default:
                                semesterText = 'Unknown Semester';
                                break;
                        }
                        var row = '<tr>' +
                            '<td>' + enroll.studentID + '</td>' +
                            '<td>' + enroll.lname + ', ' + enroll.fname + '</td>' +
                            '<td>' + enroll.progAcronym + ' ' + enroll.studYear + '-' + enroll.studSec + '</td>' +
                            '<td>' + semesterText + '</td>' +
                            '</tr>';
                        studentEnrolledTable.append(row);
                    });
                } else {
                    studentEnrolledTable.append('<tr><td colspan="4" class="text-center">No Students Enroll.</td></tr>');
                }

                $('#viewStudEnrollModal').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('An error occurred while fetching the enrollment.');
            }
        });
        $('#pdfIframe').attr('src', pdfUrl);
    });
});