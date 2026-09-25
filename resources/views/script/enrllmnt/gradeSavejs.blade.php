<script type="text/javascript">
    function reloadTableContent() {
        $('#tableBodyContent').load(window.location.href + ' #tableBodyContent > *', function() {
            // Optional: Re-attach any plugin listeners if needed
        });
    }
    function updateGrade(id, grade){
        //alert(id);
            $.ajax({
            url: '{{ route('registrarsave_grades') }}',
            method: 'POST',
            data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
            success: function (data) {
                //console.log('data.gradeCount');
                if(data.gradeCount > 0){
                    $('#submitgradeid').prop('disabled', false);
                }else{
                    $('#submitgradeid').prop('disabled', true);
                }
                reloadTableContent();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
    function updateGradeComp(id, grade){
        //alert(id);
            $.ajax({
            url: '{{ route('registrarsave_gradesComp') }}',
            method: 'POST',
            data: { id: id, grade: grade, _token: '{{ csrf_token() }}' },
            success: function (data) {
                //console.log(data.gradeCount);
                if(data.gradeCount > 0){
                    $('#submitgradeid').prop('disabled', false);
                }else{
                    $('#submitgradeid').prop('disabled', true);
                }
                reloadTableContent();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>
