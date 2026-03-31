<script>
    function loadSlots() {
        let date = $('#year').val();
        let campus = $('#campus').val();

        $.ajax({
            url: "{{ route('slots.ajax') }}",
            type: "GET",
            data: {
                date: date,
                campus: campus
            },
            success: function(response) {
                $('#slot-container').html(response);
            }
        });
    }

    $(document).ready(function() {
        loadSlots();
    });

    $('#year, #campus').on('change', function() {
        loadSlots();
    });

    setInterval(loadSlots, 60000);
</script>