$(document).ready(function() {
    $('#addStudentApply').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studentAddNewRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    Swal.fire({
                        title: 'Success!',
                        html: 'Student ID: <strong>' + response.student_id + '</strong>',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                    console.log(response);
                } else {
                    toastr.error(response.message);
                    console.log(response);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    var cityData = {
        "Manila": { province: "Metro Manila", region: "NCR", zcode: "1000" },
        "Quezon City": { province: "Metro Manila", region: "NCR", zcode: "1100" },
        "Caloocan": { province: "Metro Manila", region: "NCR", zcode: "1400" },
        "Las Piñas": { province: "Metro Manila", region: "NCR", zcode: "1740" },
        "Makati": { province: "Metro Manila", region: "NCR", zcode: "1200" },
        "Malabon": { province: "Metro Manila", region: "NCR", zcode: "1470" },
        "Mandaluyong": { province: "Metro Manila", region: "NCR", zcode: "1550" },
        "Marikina": { province: "Metro Manila", region: "NCR", zcode: "1800" },
        "Muntinlupa": { province: "Metro Manila", region: "NCR", zcode: "1770" },
        "Navotas": { province: "Metro Manila", region: "NCR", zcode: "1485" },
        "Parañaque": { province: "Metro Manila", region: "NCR", zcode: "1700" },
        "Pasay": { province: "Metro Manila", region: "NCR", zcode: "1300" },
        "Pasig": { province: "Metro Manila", region: "NCR", zcode: "1600" },
        "San Juan": { province: "Metro Manila", region: "NCR", zcode: "1500" },
        "Taguig": { province: "Metro Manila", region: "NCR", zcode: "1630" },
        "Valenzuela": { province: "Metro Manila", region: "NCR", zcode: "1440" },
        "Cebu City": { province: "Cebu", region: "Region VII", zcode: "6000" },
        "Mandaue": { province: "Cebu", region: "Region VII", zcode: "6014" },
        "Lapu-Lapu": { province: "Cebu", region: "Region VII", zcode: "6015" },
        "Davao City": { province: "Davao del Sur", region: "Region XI", zcode: "8000" },
        "Baguio City": { province: "Benguet", region: "CAR", zcode: "2600" },
        "Iloilo City": { province: "Iloilo", region: "Region VI", zcode: "5000" },
        "Zamboanga City": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7000" },

        // Negros Occidental
        "BACOLOD": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6100" },
        "BAGO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6101" },
        "BINALBAGAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6107" },
        "CADIZ": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6121" },
        "CALATRAVA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6126" },
        "CANDONI": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6110" },
        "CAUAYAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6112" },
        "DON SALVADOR BENEDICTO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6133" },
        "ENRIQUE MAGALONA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6118" },
        "ESCALANTE": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6124" },
        "HIMAMAYLAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6108" },
        "HINIGARAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6106" },
        "HINOBA-AN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6114" },
        "ILOG": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6109" },
        "ISABELA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6128" },
        "KABANKALAN CITY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6111" },
        "LA CARLOTA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6130" },
        "LA CASTELLANA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6131" },
        "MANAPLA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6120" },
        "MOISES PADILLA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6132" },
        "PARAISO FABRICA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6123" },
        "PONTEVEDRA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6105" },
        "SAGAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6122" },
        "SAN CARLOS CITY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6127" },
        "SAN ENRIQUE": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6104" },
        "SILAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6116" },
        "SILAY HAWAIIAN CENTRAL": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6117" },
        "SIPALAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6113" },
        "TALISAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6115" },
        "TOBOSO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6125" },
        "VALLADOLID": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6103" },
        "VICTORIAS": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6119" },

        // Negros Oriental
        "AMLAN": { province: "NEGROS OR.", region: "REGION VI", zcode: "6203" },
        "AYUNGON": { province: "NEGROS OR.", region: "REGION VII", zcode: "6210" },
        "BACONG": { province: "NEGROS OR.", region: "REGION VII", zcode: "6216" },
        "BAIS CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6206" },
        "BASAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6222" },
        "BAYAWAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6221" },
        "BINDOY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6209" },
        "CANLAON CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6223" },
        "DAUIN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6217" },
        "DUMAGUETE CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6200" },
        "GUIHULNGAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6214" },
        "JIMALALUD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6212" },
        "LA LIBERTAD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6213" },
        "MABINAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6207" },
        "MANJUYOD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6208" },
        "PAMPLONA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6205" },
        "SAN JOSE": { province: "NEGROS OR.", region: "REGION VII", zcode: "6202" },
        "SANTA CATALINA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6220" },
        "SIATON": { province: "NEGROS OR.", region: "REGION VII", zcode: "6219" },
        "SIBULAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6201" },
        "TANJAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6204" },
        "TAYASAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6211" },
        "VALENCIA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6215" },
        "VALLEHERMOSO": { province: "NEGROS OR.", region: "REGION VII", zcode: "6224" },
        "ZAMBOANGUITA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6218" },


        "Cagayan de Oro": { province: "Misamis Oriental", region: "Region X", zcode: "9000" },
        "General Santos": { province: "South Cotabato", region: "Region XII", zcode: "9500" },
        "Butuan": { province: "Agusan del Norte", region: "Caraga", zcode: "8600" },
        "Cotabato City": { province: "Maguindanao", region: "BARMM", zcode: "9600" },
        "Dagupan": { province: "Pangasinan", region: "Region I", zcode: "2400" },
        "Naga": { province: "Camarines Sur", region: "Region V", zcode: "4400" },
        "Olongapo": { province: "Zambales", region: "Region III", zcode: "2200" },
        "Ormoc": { province: "Leyte", region: "Region VIII", zcode: "6541" },
        "Puerto Princesa": { province: "Palawan", region: "MIMAROPA", zcode: "5300" },
        "Tacloban": { province: "Leyte", region: "Region VIII", zcode: "6500" },

        "Zamboanga City": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7000" },
        "Antipolo": { province: "Rizal", region: "CALABARZON", zcode: "1870" },
        "Lucena": { province: "Quezon", region: "CALABARZON", zcode: "4301" },
        "San Pablo": { province: "Laguna", region: "CALABARZON", zcode: "4000" },
        "Calamba": { province: "Laguna", region: "CALABARZON", zcode: "4027" },
        "Batangas City": { province: "Batangas", region: "CALABARZON", zcode: "4200" },
        "Lipa": { province: "Batangas", region: "CALABARZON", zcode: "4217" },
        "San Fernando": { province: "La Union", region: "Region I", zcode: "2500" },
        "Urdaneta": { province: "Pangasinan", region: "Region I", zcode: "2428" },
        "Vigan": { province: "Ilocos Sur", region: "Region I", zcode: "2700" },
        "Laoag": { province: "Ilocos Norte", region: "Region I", zcode: "2900" },
        "Cabanatuan": { province: "Nueva Ecija", region: "Region III", zcode: "3100" },
        "San Jose del Monte": { province: "Bulacan", region: "Region III", zcode: "3023" },
        "Angeles": { province: "Pampanga", region: "Region III", zcode: "2009" },
        "Tarlac City": { province: "Tarlac", region: "Region III", zcode: "2300" },
        "San Fernando": { province: "Pampanga", region: "Region III", zcode: "2000" },
        "Balanga": { province: "Bataan", region: "Region III", zcode: "2100" },
        "Malolos": { province: "Bulacan", region: "Region III", zcode: "3000" },
        "Meycauayan": { province: "Bulacan", region: "Region III", zcode: "3020" },
        "Gapan": { province: "Nueva Ecija", region: "Region III", zcode: "3105" },
        "San Jose": { province: "Nueva Ecija", region: "Region III", zcode: "3121" },
        "Tagum": { province: "Davao del Norte", region: "Region XI", zcode: "8100" },
        "Panabo": { province: "Davao del Norte", region: "Region XI", zcode: "8105" },
        "Samal": { province: "Davao del Norte", region: "Region XI", zcode: "8119" },
        "Digos": { province: "Davao del Sur", region: "Region XI", zcode: "8002" },
        "Mati": { province: "Davao Oriental", region: "Region XI", zcode: "8200" },
        "Tagaytay": { province: "Cavite", region: "CALABARZON", zcode: "4120" },
        "Trece Martires": { province: "Cavite", region: "CALABARZON", zcode: "4109" },
        "Dasmariñas": { province: "Cavite", region: "CALABARZON", zcode: "4114" },
        "Cavite City": { province: "Cavite", region: "CALABARZON", zcode: "4100" },
        "Biñan": { province: "Laguna", region: "CALABARZON", zcode: "4024" },
        "Santa Rosa": { province: "Laguna", region: "CALABARZON", zcode: "4026" },
        "Tagum": { province: "Davao del Norte", region: "Region XI", zcode: "8100" },
        "Valencia": { province: "Bukidnon", region: "Region X", zcode: "8709" },
        "Malaybalay": { province: "Bukidnon", region: "Region X", zcode: "8700" },
        "Surigao City": { province: "Surigao del Norte", region: "Caraga", zcode: "8400" },
        "Cabadbaran": { province: "Agusan del Norte", region: "Caraga", zcode: "8605" },
        "Bislig": { province: "Surigao del Sur", region: "Caraga", zcode: "8311" },
        "Bayugan": { province: "Agusan del Sur", region: "Caraga", zcode: "8502" },
        "Koronadal": { province: "South Cotabato", region: "Region XII", zcode: "9506" },
        "Kidapawan": { province: "North Cotabato", region: "Region XII", zcode: "9400" },
        "Tacurong": { province: "Sultan Kudarat", region: "Region XII", zcode: "9800" },
        "Valencia": { province: "Bukidnon", region: "Region X", zcode: "8709" },
        "Pagadian": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7016" },
        "Dipolog": { province: "Zamboanga del Norte", region: "Region IX", zcode: "7100" },
        "Isabela": { province: "Basilan", region: "BARMM", zcode: "7300" },
        "Iligan": { province: "Lanao del Norte", region: "Region X", zcode: "9200" },
        "Oroquieta": { province: "Misamis Occidental", region: "Region X", zcode: "7207" },
        "Ozamis": { province: "Misamis Occidental", region: "Region X", zcode: "7200" },
        "Tangub": { province: "Misamis Occidental", region: "Region X", zcode: "7214" },
        "Bais": { province: "Negros Oriental", region: "Region VII", zcode: "6206" },
        "Bayawan": { province: "Negros Oriental", region: "Region VII", zcode: "6221" },
        "Canlaon": { province: "Negros Oriental", region: "Region VII", zcode: "6223" },
        "Guihulngan": { province: "Negros Oriental", region: "Region VII", zcode: "6214" },
        "Tanjay": { province: "Negros Oriental", region: "Region VII", zcode: "6204" },
        "Toledo": { province: "Cebu", region: "Region VII", zcode: "6038" },
        "Talisay": { province: "Cebu", region: "Region VII", zcode: "6045" },
        "Naga": { province: "Cebu", region: "Region VII", zcode: "6037" },
        "Carcar": { province: "Cebu", region: "Region VII", zcode: "6019" },
        "Danao": { province: "Cebu", region: "Region VII", zcode: "6004" },
        "Bogo": { province: "Cebu", region: "Region VII", zcode: "6010" },
        "Tagbilaran": { province: "Bohol", region: "Region VII", zcode: "6300" },
        "Balanga": { province: "Bataan", region: "Region III", zcode: "2100" },
        "Tuguegarao": { province: "Cagayan", region: "Region II", zcode: "3500" },
        "Santiago": { province: "Isabela", region: "Region II", zcode: "3311" },
        "Cauayan": { province: "Isabela", region: "Region II", zcode: "3305" },
        "Baybay": { province: "Leyte", region: "Region VIII", zcode: "6521" },
        "Borongan": { province: "Eastern Samar", region: "Region VIII", zcode: "6800" },
        "Calbayog": { province: "Samar", region: "Region VIII", zcode: "6710" },
        "Catbalogan": { province: "Samar", region: "Region VIII", zcode: "6700" },
        "Maasin": { province: "Southern Leyte", region: "Region VIII", zcode: "6600" },
        "Baybay": { province: "Leyte", region: "Region VIII", zcode: "6521" },
        "Bayugan": { province: "Agusan del Sur", region: "Caraga", zcode: "8502" }
        // Add more cities as needed
    };

    var $citySelect = $('#viewdatastudCity');
    var $provinceInput = $('#viewdatastudProvince');
    var $regionInput = $('#viewdatastudRegion');
    var $zcodeInput = $('#viewdatastudZcode');

    function updateAddress() {
        var hnum = document.getElementById('viewdatastudHnum').value;
        var brgy = document.getElementById('viewdatastudBrgy').value;
        var city = document.getElementById('viewdatastudCity').value;
        var province = document.getElementById('viewdatastudProvince').value;
        var region = document.getElementById('viewdatastudRegion').value;
        var zcode = document.getElementById('viewdatastudZcode').value;

        // Concatenate the values with commas
        var address = [hnum, brgy, city, province, region, zcode].filter(Boolean).join(', ');

        // Update the Address field
        document.getElementById('viewdatastudAddress').value = address;
    }

    // Add event listeners to input fields to trigger the updateAddress function
    document.getElementById('viewdatastudHnum').addEventListener('input', updateAddress);
    document.getElementById('viewdatastudBrgy').addEventListener('input', updateAddress);
    document.getElementById('viewdatastudCity').addEventListener('change', updateAddress);
    document.getElementById('viewdatastudProvince').addEventListener('input', updateAddress);
    document.getElementById('viewdatastudRegion').addEventListener('input', updateAddress);
    document.getElementById('viewdatastudZcode').addEventListener('input', updateAddress);

    // Run the function once to ensure the Address is updated if the form is pre-filled
    //updateAddress();

    // Populate the city dropdown
    var sortedCities = Object.keys(cityData).sort();

    // Populate the city dropdown
    sortedCities.forEach(function(city) {
        $citySelect.append('<option value="' + city + '">' + city + '</option>');
    });

    // Event listener for city dropdown change
    $citySelect.change(function() {
        var selectedCity = $(this).val();
        var cityInfo = cityData[selectedCity];

        // Remove highlight class from all options
        $citySelect.find('option').removeClass('highlight');

        // Add highlight class to selected option
        $citySelect.find('option[value="' + selectedCity + '"]').addClass('highlight');
        
        if (cityInfo) {
            $provinceInput.val(cityInfo.province);
            $regionInput.val(cityInfo.region);
            $zcodeInput.val(cityInfo.zcode);
        } else {
            $provinceInput.val('');
            $regionInput.val('');
            $zcodeInput.val('');
        }
        updateAddress();
    });
});
