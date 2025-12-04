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
                data.forEach(b => $('#barangay').append(`<option value="${b.id}" data-name="${b.name}" style="text-transform: uppercase;">${b.name}</option>`));
            });
        });

        $('#barangay').on('change', function () {
            var brgyName = $(this).find(':selected').data('name');
            $('#brgy_name').val(brgyName);
            updateAddress(); // Update address
        });
    });
</script>

<script>
    function updateAddress1() {
        let hnum = $('#viewdatastudHnum1').val(); // house number input
        let barangay = $('#barangay1').find(':selected').data('name');
        let city = $('#city1').find(':selected').data('name');
        let province = $('#province1').find(':selected').data('name');
        let region = $('#region1').find(':selected').data('name');
        let zipcode = $('#zipcode1').val(); // hidden or text input for zip

        // Filter out undefined or empty values, then join with comma
        let fullAddress = [hnum, barangay, city, province, region, zipcode].filter(Boolean).join(', ');

        $('#viewdatastudAddress1').val(fullAddress);
        $('#viewdatastudHnum1').on('input', updateAddress1);
    }

    $(document).ready(function () {
        $('#region1').on('change', function () {
            var regionId = $(this).val();
            var regionName = $(this).find(':selected').data('name');
            $('#region_name1').val(regionName);
            updateAddress1(); // Update address

            $('#province1').empty().append('<option disabled selected>Loading...</option>');
            $.get(provincesRoute + '/' + regionId, function (data) {
                $('#province1').html('<option disabled selected>Select Province</option>');
                data.forEach(p => $('#province1').append(`<option value="${p.province_id}" data-name="${p.name}">${p.name}</option>`));
            });
        });

        $('#province1').on('change', function () {
            var provinceId = $(this).val();
            var provinceName = $(this).find(':selected').data('name');
            $('#province_name1').val(provinceName);
            updateAddress1(); // Update address

            $('#city1').empty().append('<option disabled selected>Loading...</option>');
            $.get(citiesRoute + '/' + provinceId, function (data) {
                $('#city1').html('<option disabled selected>Select City</option>');
                data.forEach(c => $('#city1').append(`<option value="${c.city_id}" data-name="${c.name}" data-zip="${c.zip_code}">${c.name}</option>`));
            });
        });

        $('#city1').on('change', function () {
            var cityName = $(this).find(':selected').data('name');
            var zip = $(this).find(':selected').data('zip');

            $('#city_name1').val(cityName);
            $('#zipcode1').val(zip || '');
            updateAddress1(); // Update address

            var cityId = $(this).val();
            $('#barangay1').empty().append('<option disabled selected>Loading...</option>');
            $.get(barangaysRoute + '/' + cityId, function (data) {
                $('#barangay1').html('<option disabled selected>Select Barangay</option>');
                data.forEach(b => $('#barangay1').append(`<option value="${b.id}" data-name="${b.name}" style="text-transform: uppercase;">${b.name}</option>`));
            });
        });

        $('#barangay1').on('change', function () {
            var brgyName = $(this).find(':selected').data('name');
            $('#brgy_name1').val(brgyName);
            updateAddress1(); // Update address
        });
    });
</script>