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
                    if (!response || response.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Schedule',
                            text: 'No Schedule being plotted'
                        });
                        return;
                    }
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
                    // Auto trigger mergeCellsForView after loading schedule
                    mergeCellsForView();
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

        // View Schedule button click handler
        $('#viewSchedule').click(function() {
            let scheduleHtml = $('#schedule-grid').html();
            $('#schedule-view').html('<table class="table table-bordered schedule-table">' + scheduleHtml + '</table>');
            mergeCellsForView();
            $('#viewScheduleModal').modal('show');
        });
    });
</script>