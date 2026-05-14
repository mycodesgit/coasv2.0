<script>
    $(document).ready(function() {

        let days = @json($days);
        let times = @json($times);

        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || '';
        var progCod = urlParams.get('progCod') || '';

        $('#refreshSchedule').click(function() {
            location.reload();
        });

        function loadSchedule() {
            $.ajax({
                url: '{{ route('fetchSchedule') }}',
                method: 'GET',
                data: {
                    schlyear: schlyear,
                    semester: semester,
                    progCod: progCod
                },
                success: function(response) {
                    response.forEach(function(item) {
                        let day = item.schedday;
                        let startTime = item.start_time;
                        let endTime = item.end_time;
                        let subjectInfo = item.sub_name + " " + item.subSec + " " + item.lname + ", " + item.room_name + " " + item.remarks;

                        let timeIndexStart = times.indexOf(startTime);
                        let timeIndexEnd = times.indexOf(endTime);
                        let dayIndex = days.indexOf(day);

                        for (let i = timeIndexStart; i <= timeIndexEnd; i++) {
                            $(`.time-slot[data-day="${day}"][data-time="${times[i]}"]`).addClass('highlighted occupied').text(subjectInfo);
                        }
                    });
                },
                error: function(response) {
                    toastr.error('Error loading schedule: ' + response.responseJSON.message);
                }
            });
        }

        function createScheduleGrid() {
            let grid = '<table class="table table-bordered schedule-table" style="height: 5px"><thead><tr><th style="background-color: #83a986; border: 1px solid #000; text-align: center">Time</th>';
            days.forEach(day => {
                grid += `<th class="day-label text-center" style="background-color: #e9ecef; border: 1px solid #000">${day}</th>`;
            });
            grid += '</tr></thead><tbody>';

            times.forEach(time => {
                grid += `<tr><td class="time-label text-left" width="10%" style="background-color: #e9ecef; border: 1px solid #000">${time}</td>`;
                days.forEach(day => {
                    grid += `<td class="time-slot" style="border: 1px solid #8f8f8f" data-day="${day}" data-time="${time}"></td>`;
                });
                grid += '</tr>';
            });

            grid += '</tbody></table>';
            $('#schedule-grid').html(grid);
        }

        function mergeCellsForView() {
            days.forEach(day => {
                let prevCell = null;
                let rowspanCount = 1;

                times.forEach((time, index) => {
                    let currentCell = $(`.time-slot[data-day="${day}"][data-time="${time}"]`);
                    let currentText = currentCell.html();

                    if (prevCell && currentText && prevCell.html().trim() === currentText.trim() && currentText.trim() !== '') {
                        rowspanCount++;
                        prevCell.attr('rowspan', rowspanCount);
                        currentCell.remove();
                        prevCell.css('background-color', '#d9edf7');
                    } else {
                        prevCell = currentCell;
                        rowspanCount = 1;
                    }
                });
            });

            // Center the text in the merged cells and add padding
            $('#schedule-view table td').css({
                'text-align': 'center',
                'padding': '10px',
                'vertical-align': 'middle'  // Ensures content is vertically centered in merged cells
            });
        }

        function highlightCells(startDay, startTime, endDay, endTime) {
            clearHighlights();
            let dayIndexStart = days.indexOf(startDay);
            let dayIndexEnd = days.indexOf(endDay);
            let timeIndexStart = times.indexOf(startTime);
            let timeIndexEnd = times.indexOf(endTime);

            for (let i = Math.min(dayIndexStart, dayIndexEnd); i <= Math.max(dayIndexStart, dayIndexEnd); i++) {
                for (let j = Math.min(timeIndexStart, timeIndexEnd); j <= Math.max(timeIndexStart, timeIndexEnd); j++) {
                    $(`.time-slot[data-day="${days[i]}"][data-time="${times[j]}"]`).addClass('highlight');
                }
            }
        }

        function clearHighlights() {
            $('.time-slot').removeClass('highlight');
        }

        createScheduleGrid();
        loadSchedule();

        let isDragging = false;
        let startDay, startTime, endDay, endTime;

        $('.time-slot').mousedown(function() {
            isDragging = true;
            clearHighlights();
            $(this).addClass('highlight');
            startDay = $(this).data('day');
            startTime = $(this).data('time');
            endDay = startDay;
            endTime = startTime;
        });

        $('.time-slot').mousemove(function() {
            if (isDragging) {
                let currentDay = $(this).data('day');
                let currentTime = $(this).data('time');
                highlightCells(startDay, startTime, currentDay, currentTime);
                endDay = currentDay;
                endTime = currentTime;
            }
        });

        $(document).mouseup(function() {
            if (isDragging) {
                isDragging = false;
                $('#day').val(startDay);
                $('#start_time').val(startTime);
                $('#end_time').val(endTime);

                // Check if any selected cell has the 'occupied' class
                let occupied = false;
                $('.highlight').each(function() {
                    if ($(this).hasClass('occupied')) {
                        occupied = true;
                    }
                });

                // Display the modal if no cells are occupied
                if (!occupied) {
                    $('#selected-time-range').html(`Selected Time: ${startTime} - ${endTime}<br>Day: ${startDay}`);
                    $('#scheduleModal').modal('show');
                } else {
                    toastr.warning('Selected time slot is already occupied.');
                }
                //clearHighlights();
            }
        });

        $('.time-slot').click(function() {
            clearHighlights();
            startDay = $(this).data('day');
            startTime = $(this).data('time');
            endDay = startDay;
            endTime = startTime;
            highlightCells(startDay, startTime, endDay, endTime);
            $('#day').val(startDay);
            $('#start_time').val(startTime);
            $('#end_time').val(endTime);
            
            // Display the selected time range and day in the modal
            if (!$(this).hasClass('occupied')) {
                // Display the selected time range and day in the modal
                $('#selected-time-range').html(`Selected Time: ${startTime} - ${endTime}<br>Day: ${startDay}`);
                $('#scheduleModal').modal('show');
            } else {
                toastr.warning('Selected time slot is already occupied.');
            }
        });

        $(function () {
            $('#scheduleForm').validate({
                rules: {
                    subject_id: {
                        required: true,
                    },
                    faculty_id: {
                        required: true,
                    },
                    room_id: {
                        required: true,
                    },
                    remarks: {
                        required: true,
                    },
                },
                messages: {
                    subject_id: {
                        required: "Select Subject",
                    },
                    faculty_id: {
                        required: "Select Faculty",
                    },
                    room_id: {
                        required: "Select Room",
                    },
                    remarks: {
                        required: "Select Remarks",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.col-md-12').append(error);        
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });

        $('#saveSchedule').click(function() {
            // Save the schedule via AJAX
            let formData = $('#scheduleForm').serialize();
            $.ajax({
                url: '{{ route('classSchedCreate') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        $('#scheduleModal').modal('hide');
                        clearHighlights();
                        loadSchedule();
                        $(document).trigger('subjplotAdded');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function(response) {
                    // Conflict status code
                    if (response.status === 409) {
                        let conflictMessages = response.responseJSON.conflicts.map(function(conflict) {
                            return `Conflict with:<br> 
                                    Subject: ${conflict.subject}<br>
                                    Course: ${conflict.course}<br>
                                    Faculty: ${conflict.faculty}<br>
                                    Room: ${conflict.room}<br>
                                    Day: ${conflict.schedday}<br>
                                    Time: ${conflict.start_time} - ${conflict.end_time}<br><br>`;
                        }).join('');

                        Swal.fire({
                            icon: 'error',
                            title: 'Conflict',
                            html: conflictMessages,  // Use `html` instead of `text`
                        });
                    } else if (response.status === 422) { // Validation error status code
                        let errors = response.responseJSON.errors;
                        // Loop through each validation error and show them
                        $.each(errors, function(field, messages) {
                            let element = $('[name="' + field + '"]');
                            element.addClass('is-invalid');
                            let errorElement = $('<span class="invalid-feedback"></span>').text(messages.join(' '));
                            element.closest('.col-md-12').append(errorElement);
                        });
                    } else {
                        alert('Error saving schedule: ' + response.responseJSON.message);
                    }
                }
            });
        });

        // View Schedule button click handler
        $('#viewSchedule').click(function() {
            let scheduleHtml = $('#schedule-grid').html();
            $('#schedule-view').html('<table class="table table-bordered schedule-table">' + scheduleHtml + '</table>');
            mergeCellsForView();
            $('#viewScheduleModal').modal('show');
        });

        // View Schedule button click handler
        $('#deleteSchedule').click(function() {
            $('#viewScheduletoDeleteModal').modal('show');
        });

        // Generate and download the schedule as PDF
        $('#printSchedule').click(function() {
            let scheduleHtml = $('#schedule-view').html();
            let urlParams = new URLSearchParams(window.location.search);

            // Create a form
            let form = $('<form>', {
                action: '{{ route('printSchedule') }}',
                method: 'POST',
                target: '_blank'
            });

            // Add CSRF token
            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: '{{ csrf_token() }}'
            }));

            // Add schedule HTML
            form.append($('<input>', {
                type: 'hidden',
                name: 'scheduleHtml',
                value: scheduleHtml
            }));

            // Add other parameters
            form.append($('<input>', {
                type: 'hidden',
                name: 'schlyear',
                value: urlParams.get('schlyear')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'semester',
                value: urlParams.get('semester')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'progCod',
                value: urlParams.get('progCod')
            }));

            // Append form to body and submit
            $('body').append(form);
            form.submit();
        });


        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || '';
        var progCod = urlParams.get('progCod') || '';

        var dataTable = $('#asd').DataTable({
            "ajax": {
                "url": classplottedReadRoute,
                "type": "GET",
                "data": { 
                    "schlyear": schlyear,
                    "semester": semester,
                    "progCod": progCod,
                }
            },
            responsive: false,
            lengthChange: false,
            searching: false,
            paging: false,
            "columns": [
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.sub_name} - ${row.subSec}`;
                    },
                },
                {data: 'sub_title'},
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.lname}, ${row.fname}`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.schedday}, ${row.start_time}, ${row.end_time} `;
                    },
                },
                {
                data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var disableDelete = (authUserRole != 0 && authUserFname !== row.postedBy);
                            var delLink = '<button type="button" value="' + data + '" class="btn btn-outline-danger btn-sm btn-plotdelete" ' + (disableDelete ? 'disabled' : '') + '>' +
                                '<i class="fas fa-trash"></i>' +
                                '</button>';
                            return delLink;
                        } else {
                            return data;
                        }
                    },
                width: '2%',
                className: 'text-center'
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
        $(document).on('subjplotAdded', function() {
            dataTable.ajax.reload();
        });
    });

    $(document).on('click', '.btn-plotdelete', function(e) {
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                //alert('You are about to delete item with ID: ' + id);
                $.ajax({
                    type: "GET",
                    url: classplottedDeleteRoute.replace(':id', id),
                    success: function(response) {
                        $("#tr-" + id).delay(1000).fadeOut();
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Successfully Deleted!',
                            icon: 'warning',
                            customClass: {
                                container: 'my-swal-on-top'
                            },
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            setTimeout(function() {
                                location.reload();
                            }, 5000); // 5000 milliseconds = 5 seconds

                            if(response.success) {
                                toastr.success(response.message);
                                console.log(response);
                            }
                        });
                    }
                });
            }
        })
    });

    
</script>
