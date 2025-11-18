<script>
    function updateAddress() {
        let hnum = $('#viewdatastudHnum').val(); // house number input
        let barangay = $('#barangay').find(':selected').data('name');
        let city = $('#city').find(':selected').data('name');
        let province = $('#province').find(':selected').data('name');
        let region = $('#region').find(':selected').data('name');
        let zipcode = $('#zipcode').val(); // hidden or text input for zip

        // Filter out undefined or empty values, then join with comma
        let fullAddress = [hnum, barangay, city, province, region, zipcode].filter(Boolean).join(', ');

        $('#viewdatastudAddress').val(fullAddress);
        $('#viewdatastudHnum').on('input', updateAddress);
    }

    $(document).ready(function () {
        $('#viewdatastudModal').on('shown.bs.modal', function () {
            $('#region, #province, #city, #barangay').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#viewdatastudModal')
            });
        });
        $('#region').on('change', function () {
            var regionId = $(this).val();
            var regionName = $(this).find(':selected').data('name');
            $('#region_name').val(regionName);
            updateAddress(); // Update address

            $('#province').empty().append('<option disabled selected>Loading...</option>');
            $.get(provincesRoute + '/' + regionId, function (data) {
                $('#province').html('<option disabled selected>Select Province</option>');
                data.forEach(p => $('#province').append(`<option value="${p.province_id}" data-name="${p.name}">${p.name}</option>`));
            });
        });

        $('#province').on('change', function () {
            var provinceId = $(this).val();
            var provinceName = $(this).find(':selected').data('name');
            $('#province_name').val(provinceName);
            updateAddress(); // Update address

            $('#city').empty().append('<option disabled selected>Loading...</option>');
            $.get(citiesRoute + '/' + provinceId, function (data) {
                $('#city').html('<option disabled selected>Select City</option>');
                data.forEach(c => $('#city').append(`<option value="${c.city_id}" data-name="${c.name}" data-zip="${c.zip_code}">${c.name}</option>`));
            });
        });

        $('#city').on('change', function () {
            var cityName = $(this).find(':selected').data('name');
            var zip = $(this).find(':selected').data('zip');

            $('#city_name').val(cityName);
            $('#zipcode').val(zip || '');
            updateAddress(); // Update address

            var cityId = $(this).val();
            $('#barangay').empty().append('<option disabled selected>Loading...</option>');
            $.get(barangaysRoute + '/' + cityId, function (data) {
                $('#barangay').html('<option disabled selected>Select Barangay</option>');
                data.forEach(b => $('#barangay').append(`<option value="${b.id}" data-name="${b.name}">${b.name}</option>`));
            });
        });

        $('#barangay').on('change', function () {
            var brgyName = $(this).find(':selected').data('name');
            $('#brgy_name').val(brgyName);
            updateAddress(); // Update address
        });
    });
</script>