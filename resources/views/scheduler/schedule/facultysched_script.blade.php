<script>
    $(document).ready(function() {

        let days = @json($days);
        let times = @json($times);

        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || '';
        var faculty_id = urlParams.get('faculty_id') || '';

        $('#refreshSchedule').click(function() {
            location.reload();
        });

        function loadSchedule() {
            $.ajax({
                url: '{{ route('fetchFacultySchedule') }}',
                method: 'GET',
                data: {
                    schlyear: schlyear,
                    semester: semester,
                    faculty_id: faculty_id
                },
                success: function(response) {
                    response.forEach(function(item) {
                        let day = item.schedday;
                        let startTime = item.start_time;
                        let endTime = item.end_time;
                        let subjectInfo = item.sub_name + " " + item.subSec + ", " + item.room_name + " " + item.remarks;

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
                    faculty_id: {
                        required: true,
                    },
                    progcodename: {
                        required: true,
                    },
                    subject_id: {
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
                    faculty_id: {
                        required: "Select Subject",
                    },
                    progcodename: {
                        required: "Select Course",
                    },
                    subject_id: {
                        required: "Select Subject",
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
            
            // Show loading state
            let saveBtn = $(this);
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            $.ajax({
                url: '{{ route('facultySchedCreate') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success('<i class="fas fa-check-circle"></i> ' + response.message);
                        $('#scheduleModal').modal('hide');
                        clearHighlights();
                        loadSchedule();
                        $(document).trigger('subjplotAdded');
                    } else {
                        toastr.error('<i class="fas fa-times-circle"></i> Error: ' + response.message);
                    }
                    saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Schedule');
                },
                error: function(response) {
                    saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Schedule');
                    
                    // Handle validation errors (422)
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        
                        $.each(errors, function(field, messages) {
                            let element = $('[name="' + field + '"]');
                            element.addClass('is-invalid');
                            let errorElement = $('<span class="invalid-feedback"></span>').text(messages.join(' '));
                            element.closest('.col-md-12').append(errorElement);
                        });
                    } 
                    // Handle conflicts (409)
                    else if (response.status === 409) {
                        let conflicts = response.responseJSON.conflicts;
                        let conflictHtml = '';
                        
                        // Group conflicts by type
                        let groupedConflicts = {
                            'time_conflict': [],
                            'room_conflict': [],
                            'faculty_conflict': [],
                            'merge_conflict': [],
                            'faculty_change': [],
                            'program_section_conflict': [],
                        };
                        
                        $.each(conflicts, function(index, conflict) {
                            if (groupedConflicts[conflict.type]) {
                                groupedConflicts[conflict.type].push(conflict);
                            }
                        });
                        
                        // Build conflict HTML
                        let hasConflicts = false;
                        
                        // Program Section Conflicts
                        if (groupedConflicts.program_section_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-program-section">
                                    <h6 class="text-success"><i class="fas fa-graduation-cap"></i> Program/Section Conflict</h6>
                            `;
                            $.each(groupedConflicts.program_section_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Subject:</strong></span>
                                                <span class="conflict-value"><strong><i>${conflict.subject}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Section:</strong></span>
                                                <span class="conflict-value"><strong><i>${conflict.course}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Faculty:</strong></span>
                                                <span class="conflict-value"><strong><i>${conflict.faculty}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Room:</strong></span>
                                                <span class="conflict-value"><strong class="text-warning"><i>${conflict.room}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Day:</strong></span>
                                                <span class="conflict-value"><i>${conflict.schedday}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Time:</strong></span>
                                                <span class="conflict-value"><i>${conflict.start_time} - ${conflict.end_time}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Message:</strong></span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }

                        // Time Conflicts
                        if (groupedConflicts.time_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-time">
                                    <h6 class="text-danger"><i class="fas fa-clock"></i> Time Conflicts</h6>
                            `;
                            $.each(groupedConflicts.time_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-book"></i> Subject:</span>
                                                <span class="conflict-value"><strong>${conflict.subject}</strong> (${conflict.course})</span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-user-tie"></i> Faculty:</span>
                                                <span class="conflict-value">${conflict.faculty}</span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-door-open"></i> Room:</span>
                                                <span class="conflict-value"><strong class="text-warning"><i>${conflict.room}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-calendar-day"></i> Day:</span>
                                                <span class="conflict-value">${conflict.schedday}</span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-hourglass-half"></i> Time:</span>
                                                <span class="conflict-value">${conflict.start_time} - ${conflict.end_time}</span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><i class="fas fa-info-circle"></i> Message:</span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }
                        
                        // Room Conflicts
                        if (groupedConflicts.room_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-room">
                                    <h6 class="text-success"><i class="fas fa-building"></i> Room Conflicts</h6>
                            `;
                            $.each(groupedConflicts.room_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Subject: </strong></span>
                                                <span class="conflict-value"><strong>${conflict.subject}</strong><i>(${conflict.course})</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Faculty:</strong></span>
                                                <span class="conflict-value"><i>${conflict.faculty}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Room:</strong></span>
                                                <span class="conflict-value"><strong class="text-warning"><i>${conflict.room}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Day:</strong></span>
                                                <span class="conflict-value"><i>${conflict.schedday}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Time:</strong></span>
                                                <span class="conflict-value"><i>${conflict.start_time} - ${conflict.end_time}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Message:</strong></span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }
                        
                        // Faculty Conflicts
                        if (groupedConflicts.faculty_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-faculty">
                                    <h6 class="text-success"><i class="fas fa-user-graduate"></i> Faculty Conflicts</h6>
                            `;
                            $.each(groupedConflicts.faculty_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Subject:</strong></span>
                                                <span class="conflict-value"><i><strong>${conflict.subject}</strong> (${conflict.course})</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Faculty:</strong></span>
                                                <span class="conflict-value"><i>${conflict.faculty}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Room:</strong></span>
                                                <span class="conflict-value"><strong class="text-warning"><i>${conflict.room}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Day:</strong></span>
                                                <span class="conflict-value"><i>${conflict.schedday}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Time:</strong></span>
                                                <span class="conflict-value"><i>${conflict.start_time} - ${conflict.end_time}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Message:</strong></span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }
                        
                        // Merge Conflicts
                        if (groupedConflicts.merge_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-merge">
                                    <h6 class="text-primary"><i class="fas fa-code-branch"></i> Merge Conflicts</h6>
                            `;
                            $.each(groupedConflicts.merge_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Subject: </strong></span>
                                                <span class="conflict-value"><i><strong>${conflict.subject}</strong> (${conflict.course})</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Faculty:</strong></span>
                                                <span class="conflict-value"><i>${conflict.faculty}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Room:</strong></span>
                                                <span class="conflict-value"><strong class="text-warning"><i>${conflict.room}</i></strong></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Day:</strong></span>
                                                <span class="conflict-value"><i>${conflict.schedday}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Time:</strong></span>
                                                <span class="conflict-value"><i>${conflict.start_time} - ${conflict.end_time}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Message:</strong></span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }
                        
                        // Faculty Change Warning
                        if (groupedConflicts.faculty_change.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-faculty-change">
                                    <h6 class="text-success"><i class="fas fa-exchange-alt"></i> Faculty Change Warning</h6>
                            `;
                            $.each(groupedConflicts.faculty_change, function(i, conflict) {
                                conflictHtml += `
                                    <div class="bg-light p-2 mb-2 border rounded">
                                        <div class="conflict-details">
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Subject:</strong></span>
                                                <span class="conflict-value"><i>${conflict.subject}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Course:</strong></span>
                                                <span class="conflict-value"><i>${conflict.course}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Faculty:</strong></span>
                                                <span class="conflict-value"><i>${conflict.faculty}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Day:</strong></span>
                                                <span class="conflict-value"><i>${conflict.schedday}</i></span>
                                            </div>
                                            <div class="conflict-row">
                                                <span class="conflict-label"><strong>Message:</strong></span>
                                                <span class="conflict-message text-danger"><i>${conflict.message}</i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            });
                            conflictHtml += '</div>';
                        }
                        
                        if (!hasConflicts) {
                            conflictHtml = '<p class="text-center text-muted"><i class="fas fa-check-circle text-success"></i> No conflicts found.</p>';
                        }
                        
                        // Show Swal with conflicts
                        Swal.fire({
                            icon: 'error',
                            title: 'Schedule Conflicts Detected',
                            html: `
                                <div style="text-align: left; max-height: 500px; overflow-y: auto; padding: 5px;">
                                    ${conflictHtml}
                                </div>
                            `,
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: '<i class="fas fa-times"></i> Close',
                            customClass: {
                                popup: 'conflict-dialog'
                            },
                            width: 750,
                        });
                        
                    } else {
                        // Other errors
                        Swal.fire({
                            icon: 'error',
                            title: '<i class="fas fa-times-circle text-danger"></i> Error',
                            text: response.responseJSON.message || 'Error saving schedule',
                            confirmButtonText: '<i class="fas fa-check"></i> OK'
                        });
                    }
                }
            });
        });

        // View Schedule button click handler
        $('#viewSchedule').click(function() {
            let scheduleHtml = $('#schedule-grid').html();
            $('#schedule-view').html('<table class="table table-bordered schedule-table">' + scheduleHtml + '</table>');
            mergeCellsForView();
            $('#viewfacultyScheduleModal').modal('show');
        });

        // View Faculty Load button click handler
        $('#viewFacultyLoad').click(function() {
            $('#viewFacultyLoadModal').modal('show');
        });

        // Generate and download the schedule as PDF
        $('#printSchedule').click(function() {
            let scheduleHtml = $('#schedule-view').html();
            let urlParams = new URLSearchParams(window.location.search);

            // Create a form
            let form = $('<form>', {
                action: '{{ route('printFacultySchedule') }}',
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
                name: 'faculty_id',
                value: urlParams.get('faculty_id')
            }));

            // Append form to body and submit
            $('body').append(form);
            form.submit();
        });
    });
</script>
