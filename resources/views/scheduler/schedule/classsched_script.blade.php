<script>
    $(document).ready(function() {

        let days = @json($days);
        let times = @json($times);

        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || '';
        var progCod = urlParams.get('progCod') || '';

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
                        let subjectInfo = item.sub_name + " " + item.subSec + " " + item.lname + " " + item.room_name;

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

        let grid = '<table class="table table-bordered" style="height: 5px"><thead><tr><th style="background-color: #83a986; border: 1px solid #000; text-align: center">Time</th>';
        days.forEach(day => {
            grid += `<th class="day-label text-center" style="background-color: #e9ecef; border: 1px solid #000"">${day}</th>`;
        });
        grid += '</tr></thead><tbody>';

        times.forEach(time => {
            grid += `<tr><td class="time-label text-left" width="10%" style="background-color: #e9ecef; border: 1px solid #000"">${time}</td>`;
            days.forEach(day => {
                grid += `<td class="time-slot" style="border: 1px solid #8f8f8f"" data-day="${day}" data-time="${time}"></td>`;
            });
            grid += '</tr>';
        });

        grid += '</tbody></table>';
        $('#schedule-grid').html(grid);

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
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function(response) {
                    // Conflict status code
                    if (response.status === 409) {
                        let conflictMessages = response.responseJSON.conflicts.map(function(conflict) {
                            return `Conflict with Subject: ${conflict.subject} - ${conflict.course}, Faculty: ${conflict.faculty}, Room: ${conflict.room}`;
                        }).join('\n');
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Conflict',
                            text: 'Schedule conflict detected.\n' + conflictMessages,
                        });
                    } else {
                        alert('Error saving schedule: ' + response.responseJSON.message);
                    }
                }
            });
        });

        function mergeCellsForView() {
            $('#schedule-view table').each(function() {
                let table = $(this);
                let rows = table.find('tbody tr');
                
                for (let colIndex = 1; colIndex < days.length + 1; colIndex++) {
                    let prevCell = null;
                    let rowspanCount = 1;

                    for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
                        let currentCell = $(rows[rowIndex]).find('td').eq(colIndex);
                        let currentText = currentCell.html();  // Use .html() to compare the full content including line breaks

                        if (prevCell && currentText === prevCell.html() && currentText.trim() !== '') {
                            rowspanCount++;
                            prevCell.attr('rowspan', rowspanCount);
                            currentCell.remove();
                        } else {
                            prevCell = currentCell;
                            rowspanCount = 1;
                        }
                    }
                }
            });

            // Center the text in the merged cells and add padding
            $('#schedule-view table td').css({
                'text-align': 'center',
                'padding': '10px',
                'vertical-align': 'middle'  // Ensures content is vertically centered in merged cells
            });
        }


        $('#viewSchedule').click(function() {
            let scheduleHtml = $('#schedule-grid').html();
            $('#schedule-view').html('<table class="table table-bordered">' + scheduleHtml + '</table>');
            mergeCellsForView();
            $('#viewScheduleModal').modal('show');
        });

        // Generate and download the schedule as PDF
        $('#printSchedule').click(function() {
            let doc = new jsPDF('landscape');
            let scheduleHtml = $('#schedule-grid').html();
            
            doc.html(scheduleHtml, {
                callback: function (doc) {
                    doc.save('schedule.pdf');
                },
                x: 10,
                y: 10
            });
        });
        loadSchedule();
    });
</script>