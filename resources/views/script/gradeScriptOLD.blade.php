<script>
    $(document).ready(function () {
        $('#subjectDropdown').change(function () {
            var selectedOption = $(this).find(':selected');

            $('#descriptiveTitle').val(selectedOption.data('desc'));
            $('#unit').val(selectedOption.data('unit'));

            var sid = selectedOption.val();
            subJect(sid);
        });
    });

    function subJect(sid) {
        $.ajax({
            url: '{{ route('geneStudent') }}',
            method: 'POST',
            data: { sid: sid, _token: '{{ csrf_token() }}' },
            success: function (data) {
                populateTable(data.studgradesData);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }


    function populateTable(studgradesData) {
        var tableBody = $('#studgradesTableBody');
        tableBody.empty();
        var counter = 1;

       $.each(studgradesData, function (index, grade) {
            var fullName = grade.lname + ', ' + grade.fname + ' ' + (grade.mname ? grade.mname.charAt(0) + '.' : '');
            var row = '<tr>';
            row += '<td>' + (index + 1) + '</td>';
            row += '<td>' + grade.studID + '</td>';
            row += '<td><strong>' + fullName + '</strong></td>';
            row += '<td>' + grade.subjFgrade + '</td>';
            row += '<td>' + grade.subjComp + '</td>';
            row += '<td><strong>' + grade.creditEarned + '</strong></td>';
            row += '<td><select class="form-control form-control-sm"><option value=""></option> @foreach ($grdCode as $grdCodes)<option value="{{ $grdCodes->grade }}">{{ $grdCodes->grade }}</option>@endforeach</select></td>';
            row += '</tr>';
            tableBody.append(row);
        });
    }
</script>