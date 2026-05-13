<script>
    $(document).ready(function () {
        $('#confirmButton').click(function () {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to confirm this student appraisal and forward to registrar office!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm & forward it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pushtoregistrar.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            schlyear: "{{ $schlyear ?? '' }}",
                            semester: "{{ $semester ?? '' }}",
                            campus: "{{ $campus ?? '' }}"
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then(() => {
                                window.location.href = response.redirect_url;
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message
                            });
                        }
                    });
                }
            });
        });
    });
</script>