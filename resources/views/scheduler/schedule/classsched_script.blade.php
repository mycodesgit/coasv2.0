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
            
            // Show loading state
            let saveBtn = $(this);
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            $.ajax({
                url: '{{ route('classSchedCreate') }}',
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
                            'faculty_change': []
                        };
                        
                        $.each(conflicts, function(index, conflict) {
                            if (groupedConflicts[conflict.type]) {
                                groupedConflicts[conflict.type].push(conflict);
                            }
                        });
                        
                        // Build conflict HTML
                        let hasConflicts = false;
                        
                        // Time Conflicts
                        if (groupedConflicts.time_conflict.length > 0) {
                            hasConflicts = true;
                            conflictHtml += `
                                <div class="conflict-category conflict-time">
                                    <h6 class="text-danger"><i class="fas fa-clock"></i> Time Conflicts</h6>
                            `;
                            $.each(groupedConflicts.time_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="conflict-item">
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
                                                <span class="conflict-value">${conflict.room}</span>
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
                                                <span class="conflict-message">${conflict.message}</span>
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
                                    <h6 class="text-info"><i class="fas fa-user-graduate"></i> Faculty Conflicts</h6>
                            `;
                            $.each(groupedConflicts.faculty_conflict, function(i, conflict) {
                                conflictHtml += `
                                    <div class="conflict-item">
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
                                                <span class="conflict-value"><i>${conflict.room}</i></span>
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
                                                <span class="conflict-message"><i>${conflict.message}</i></span>
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
                                                <span class="conflict-value"><i>${conflict.room}</i></span>
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
                                                <span class="conflict-message"><i>${conflict.message}</i></span>
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
                        
                        // Add action buttons
                        conflictHtml += `
                            <div class="conflict-actions mt-3">
                                <button class="btn btn-warning btn-sm" onclick="forceSaveSchedule()">
                                    <i class="fas fa-exclamation-triangle"></i> Force Save (Override Conflicts)
                                </button>
                                <button class="btn btn-secondary btn-sm" onclick="closeConflictModal()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        `;
                        
                        // Show Swal with conflicts
                        Swal.fire({
                            icon: 'error',
                            title: '<i class="fas fa-exclamation-circle text-danger"></i> Schedule Conflicts Detected',
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

        // Force Save Function
        function forceSaveSchedule() {
            Swal.close();
            
            Swal.fire({
                title: '<i class="fas fa-exclamation-triangle text-warning"></i> Force Save Schedule?',
                text: "This will override existing conflicts. Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-check"></i> Yes, force save!',
                cancelButtonText: '<i class="fas fa-times"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = $('#scheduleForm').serialize();
                    formData += '&force_save=true';
                    
                    let saveBtn = $('#saveSchedule');
                    saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Force Saving...');
                    
                    $.ajax({
                        url: '{{ route('classSchedCreate') }}',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if(response.success) {
                                toastr.success('<i class="fas fa-check-circle"></i> Schedule force saved successfully!');
                                $('#scheduleModal').modal('hide');
                                clearHighlights();
                                loadSchedule();
                                $(document).trigger('subjplotAdded');
                            } else {
                                toastr.error('<i class="fas fa-times-circle"></i> Failed to force save: ' + response.message);
                            }
                            saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Schedule');
                        },
                        error: function(response) {
                            toastr.error('<i class="fas fa-times-circle"></i> Failed to force save: ' + (response.responseJSON.message || 'Unknown error'));
                            saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Schedule');
                        }
                    });
                }
            });
        }

        function closeConflictModal() {
            Swal.close();
        }

        // Real-time conflict checking
        function checkRealTimeConflicts() {
            let formData = $('#scheduleForm').serialize();
            formData += '&check_only=true';
            
            $.ajax({
                url: '{{ route('classSchedCreate') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.has_conflicts) {
                        let conflictTypes = [];
                        let grouped = response.conflicts || [];
                        
                        // Count conflict types
                        let timeCount = grouped.filter(c => c.type === 'time_conflict').length;
                        let roomCount = grouped.filter(c => c.type === 'room_conflict').length;
                        let facultyCount = grouped.filter(c => c.type === 'faculty_conflict').length;
                        let mergeCount = grouped.filter(c => c.type === 'merge_conflict').length;
                        let changeCount = grouped.filter(c => c.type === 'faculty_change').length;
                        
                        if (timeCount > 0) conflictTypes.push(`${timeCount} Time`);
                        if (roomCount > 0) conflictTypes.push(`${roomCount} Room`);
                        if (facultyCount > 0) conflictTypes.push(`${facultyCount} Faculty`);
                        if (mergeCount > 0) conflictTypes.push(`${mergeCount} Merge`);
                        if (changeCount > 0) conflictTypes.push(`${changeCount} Faculty Change`);
                        
                        $('#conflictWarning').html(`
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <strong>${grouped.length} conflict(s) detected:</strong>
                            <span class="badge badge-danger">${conflictTypes.join(', ')}</span>
                            <button class="btn btn-sm btn-link" onclick="viewConflicts()"><i class="fas fa-eye"></i> View Details</button>
                        `).show();
                        $('#conflictCount').text(grouped.length);
                    } else {
                        $('#conflictWarning').hide();
                    }
                }
            });
        }

        function viewConflicts() {
            $('#saveSchedule').click();
        }

        // Auto-check conflicts on form change
        $(document).ready(function() {
            let conflictCheckTimer;
            
            $('#scheduleForm input, #scheduleForm select').on('change', function() {
                clearTimeout(conflictCheckTimer);
                conflictCheckTimer = setTimeout(function() {
                    let requiredFields = ['schedday', 'start_time', 'end_time', 'faculty_id', 'room_id', 'subject_id'];
                    let allFilled = true;
                    
                    $.each(requiredFields, function(i, field) {
                        if (!$('[name="' + field + '"]').val()) {
                            allFilled = false;
                            return false;
                        }
                    });
                    
                    if (allFilled) {
                        checkRealTimeConflicts();
                    }
                }, 500);
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
