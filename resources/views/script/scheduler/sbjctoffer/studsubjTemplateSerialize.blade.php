<script>
    $(document).ready(function() {
        // Ensure Select2 is initialized (add this if not already present elsewhere)
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $('#studSubjectShowTemplate').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Use Select2's val method for proper value retrieval
            var selectedSubSec = $('#subSecSelect').select2('val') || $('#subSecSelect').val();
            var progCode = $('#subSecSelect option:selected').data('prog-code'); // Get progCode from data attribute
            var year = $('#subSecSelect option:selected').data('year'); // Get year from data attribute (e.g., "1")
            var campus = $('input[name="campus"]').val();
            var semester = $('input[name="semester"]').val();

            // Debug: Log the values to console (check browser dev tools > Console)
            console.log('Selected subSec:', selectedSubSec);
            console.log('progCode:', progCode);
            console.log('Year:', year);
            console.log('Campus:', campus);
            console.log('Semester:', semester);

            if (!selectedSubSec || selectedSubSec === null || selectedSubSec === '---Select---') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select a Subject Year & Section.'
                });
                return;
            }

            if (!progCode || !year) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Missing program code or year data.'
                });
                return;
            }

            // AJAX call to fetch subjects by progCode and year 1 (LIKE for subSec)
            $.ajax({
                url: '{{ route("get.subjects.by.prog.year", ["progcode" => ":progcode", "year" => ":year", "campus" => ":campus", "semester" => ":semester"]) }}'
                    .replace(':progcode', encodeURIComponent(progCode))
                    .replace(':year', year)
                    .replace(':campus', campus)
                    .replace(':semester', semester),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var tbody = $('#subjectsTableBody');
                    tbody.empty(); // Clear existing rows

                    if (data.length === 0) {
                        tbody.append('<tr><td colspan="15" class="text-center">No subjects found for Year ' + year + ' (All Sections).</td></tr>');
                    } else {
                        $.each(data, function(index, subject) {
                            var row = '<tr>' +
                                '<td>' + (subject.subCode || '') + '</td>' +
                                '<td>' + (subject.sub_name || '') + '</td>' +
                                '<td>' + (subject.sub_title || '') + '</td>' +
                                '<td>' + (subject.lecUnit || '') + '</td>' +
                                '<td>' + (subject.labUnit || '') + '</td>' +
                                '<td>' + (subject.subUnit || '') + '</td>' +
                                '<td>' + (subject.lecFee || '') + '</td>' +
                                '<td>' + (subject.labFee || '') + '</td>' +
                                '<td>' + (subject.devFee || '') + '</td>' +
                                '<td>' + (subject.itfee || '') + '</td>' +
                                '<td>' + (subject.fund || '') + '</td>' +
                                '<td>' + (subject.fundAccount || '') + '</td>' +
                                '<td>' + (subject.isOJT ? 'Yes' : 'No') + '</td>' +
                                '<td>' + (subject.isTemp ? 'Yes' : 'No') + '</td>' +
                                '<td>' + (subject.isType || '') + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    }

                    // Update modal title to reflect year-level view
                    $('#subjectsModalLabel').text('Subjects for Year ' + year + ' (All Sections)');

                    $('#subjectsModal').modal('show'); // Show the modal
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error Details:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error fetching subjects: ' + error + '. Check console for details.'
                    });
                }
            });
        });

        // Save button logic remains the same (uses selectedSubSec for section-specific saving)
        $('#saveSubjectsBtn').on('click', function() {
            var rows = $('#subjectsTableBody tr').get(); // Get all rows
            if (rows.length === 0 || $(rows[0]).find('td').first().text() === 'No subjects found') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'No subjects to save.'
                });
                return;
            }

            var subjectsData = [];
            $.each(rows, function(index, row) {
                var cells = $(row).find('td');
                if (cells.length >= 13) { // Ensure full row
                    subjectsData.push({
                        subCode: cells.eq(0).text().trim() || '', // Assuming subCode is in first column; adjust if Desc is separate
                        subName: cells.eq(1).text().trim() || '', // Assuming subCode is in first column; adjust if Desc is separate
                        desc: cells.eq(2).text().trim() || '', // Desc if needed (not in model, but for reference)
                        lecUnit: cells.eq(3).text().trim() || 0,
                        labUnit: cells.eq(4).text().trim() || 0,
                        subUnit: cells.eq(5).text().trim() || 0,
                        lecFee: cells.eq(6).text().trim() || 0,
                        labFee: cells.eq(7).text().trim() || 0,
                        devFee: cells.eq(8).text().trim() || 0,
                        itfee: cells.eq(9).text().trim() || 0,
                        fund: cells.eq(10).text().trim() || '',
                        fundAccount: cells.eq(11).text().trim() || '',
                        isOJT: cells.eq(12).text().trim() || '',
                        isTemp: cells.eq(13).text().trim() || '',
                        isType: cells.eq(14).text().trim() || '',
                        // isType: if you add column, extract here
                    });
                }
            });

            var commonData = {
                subSec: $('#subjectsModalLabel').text().replace('Subjects for ', '').trim(), // Extract from title, e.g., "AB EL 1-A"
                schlyear: $('#modalSchlyear').val(),
                semester: $('#modalSemester').val(),
                campus: $('#modalCampus').val(),
                postedBy: $('#modalPostedBy').val(),
                maxstud: 50 // Default; adjust or make configurable
            };

            // AJAX to save
            $.ajax({
                url: '{{ route("save.subjects.offered") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    subjects: subjectsData,
                    common: commonData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Subjects saved successfully! ' + response.savedCount + ' records created.'
                        }).then(() => {
                            $(document).trigger('subjOffAdded');
                            $('#subjectsModal').modal('hide'); // Close modal
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + (response.message || 'Failed to save.')
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Save Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error saving subjects: ' + error
                    });
                }
            });
        });
    });
</script>