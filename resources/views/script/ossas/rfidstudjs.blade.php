<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    $(document).ready(function() {

        const scanner = document.getElementById("rfidScanner");
        const display = document.getElementById("studentUniqueRFID");
        let lastScannedRFID = "";

        scanner.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); 

                let currentRFID = scanner.value.trim();

                // Prevent duplicate scan
                if(currentRFID !== "" && currentRFID !== lastScannedRFID) {
                    display.value = currentRFID;
                    lastScannedRFID = currentRFID;
                }

                scanner.value = ""; 
            }
        });

        $('#adRFIDstud').submit(function(event) {
            event.preventDefault();

            // if(display.value.trim() === "") {
            //     toastr.error("Please scan the RFID card before saving.");
            //     return;
            // }

            var formData = new FormData(this); 

            $.ajax({
                url: rfidstudentCreateRoute,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);

                        //$('#adRFIDstud')[0].reset();
                        lastScannedRFID = "";
                        $('#stdntID').focus();
                    } else {
                        toastr.error(response.message);
                        console.log(response);
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                }
            });
        });

    });
</script>

<script>
    // ============================================================
    // 1. STUDENT ID FORMATTING FUNCTIONS
    // ============================================================
    function formatInput(input) {
        let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');

        if (cleaned.length > 0) {
            let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
            input.value = formatted;
        } else {
            input.value = '';
        }
    }

    function handleDelete(event) {
        if (event.key === 'Backspace') {
            let input = event.target;
            let value = input.value;
            input.value = value.substring(0, value.length - 1);
            formatInput(input);
        }
    }

    // ============================================================
    // 2. GLOBAL VARIABLES
    // ============================================================
    let currentEncryptedId = '';

    // ============================================================
    // 3. QR CODE GENERATOR
    // ============================================================
    function generateQR(value) {
        const qrContainer = document.getElementById('qrcode');
        qrContainer.innerHTML = '';
        if (!value) return;
        new QRCode(qrContainer, {
            text: value,
            width: 80,
            height: 80,
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // ============================================================
    // 4. FETCH STUDENT DATA - FIXED (removed .textContent on images)
    // ============================================================
    function fetchStudentName(studid) {
        if (!studid) return;

        const url = `{{ route('getossaStudentById', ['id' => ':id']) }}`.replace(':id', studid);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('studentName').value = 'Student not found';
                    document.getElementById('studentCourse').value = 'Data not found';
                    document.getElementById('studentCivilStatus').value = 'Data not found';
                    document.getElementById('studentAddress').value = 'Data not found';

                    document.getElementById('studentCardName').textContent = 'Data not found';
                    document.getElementById('studentCardNo').textContent = '';
                    document.getElementById('studentCardCourse').textContent = '';
                    document.getElementById('studentCardAddress').textContent = '';
                    document.getElementById('studentCardAddressPreview').textContent = '';
                    document.getElementById('studentCardBirthday').textContent = '';
                    document.getElementById('studentCardBirthdayPreview').textContent = '';
                    document.getElementById('studentCardContact').textContent = '';
                    document.getElementById('studentCardContactPreview').textContent = '';
                    
                    // FIX: Hide signature images instead of using textContent
                    var sig1 = document.getElementById('studentCardSignature');
                    var sig2 = document.getElementById('studentCardSignaturePreview');
                    if (sig1) { sig1.style.display = 'none'; sig1.src = ''; }
                    if (sig2) { sig2.style.display = 'none'; sig2.src = ''; }

                    document.getElementById('qrcode').innerHTML = '';
                    currentEncryptedId = '';
                } else {
                    const fullName = `${data.lname}, ${data.fname}${data.mname ? ' ' + data.mname.charAt(0) + '.' : ''}${data.ext && data.ext.toLowerCase() !== 'n/a' ? ' ' + data.ext : ''}`.toUpperCase();
                    const civilStatus = `${data.civil_status}`.toUpperCase();
                    const progName = `${data.progAcronym || ''}`
                        .replace(/BACHELOR OF ARTS/i, 'BA')
                        .replace(/BACHELOR OF SCIENCE/i, 'BS')
                        .replace(/BACHELOR OF SECONDARY/i, 'BS')
                        .replace(/BACHELOR OF ELEMENTARY EDUCATION/i, 'BEED')
                        .replace(/BACHELOR OF SCIENCE IN AGRICULTURAL AND BIOSYSTEMS ENGINEERING/i, 'BSABE')
                        .toUpperCase();
                    const address = `${data.address}`.toUpperCase();

                    // TEXT FIELDS
                    document.getElementById('studentName').value = fullName;
                    document.getElementById('studentCourse').value = progName;
                    document.getElementById('studentCivilStatus').value = civilStatus;
                    document.getElementById('studentAddress').value = address;

                    document.getElementById('studentCardName').innerHTML = fullName.replace(/, /g, ',<br>');
                    document.getElementById('studentCardNo').textContent = data.stud_id;
                    document.getElementById('studentCardCourse').textContent = progName;
                    document.getElementById('studentCardAddress').textContent = address;
                    document.getElementById('studentCardAddressPreview').textContent = address;
                    document.getElementById('studentCardBirthday').textContent = data.bday;
                    document.getElementById('studentCardBirthdayPreview').textContent = data.bday;
                    document.getElementById('studentCardContact').textContent = data.contact;
                    document.getElementById('studentCardContactPreview').textContent = data.contact;
                    
                    // FIX: DO NOT use .textContent on images - keep them as image elements
                    // The signature images will be updated by the capture function

                    // STORE ENCRYPTED ID
                    currentEncryptedId = data.encrypted_id;
                    generateQR(currentEncryptedId);
                    console.log("QR VALUE:", currentEncryptedId);
                    document.getElementById('rfidScanner').focus();
                }
            })
            .catch(error => {
                console.error('Error fetching student:', error);
            });
    }

    // ============================================================
    // 5. CONTACT PERSON EVENT LISTENER
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const contactPersonInput = document.getElementById('studentContactPerson');
        const contactNumberInput = document.getElementById('studentContactPersonNo');

        const displayPersonElement = document.getElementById('studentCardContactPerson');
        const displayNumberElement = document.getElementById('studentCardContactNumber');
        const displayPersonElementPreview = document.getElementById('studentCardContactPersonPreview');
        const displayNumberElementPreview = document.getElementById('studentCardContactPersonNoPreview');
        
        if (contactPersonInput && displayPersonElement) {
            displayPersonElement.textContent = contactPersonInput.value || '\u00A0';
            displayPersonElementPreview.textContent = contactPersonInput.value || '\u00A0';
            contactPersonInput.addEventListener('input', function() {
                const value = this.value || '\u00A0';
                displayPersonElement.textContent = value;
                displayPersonElementPreview.textContent = value;
            });
        }
        
        if (contactNumberInput && displayNumberElement) {
            displayNumberElement.textContent = contactNumberInput.value || '\u00A0';
            displayNumberElementPreview.textContent = contactNumberInput.value || '\u00A0';
            contactNumberInput.addEventListener('input', function() {
                const value = this.value || '\u00A0';
                displayNumberElement.textContent = value;
                displayNumberElementPreview.textContent = value;
            });
        }
    });

    // ============================================================
    // 6. PREVIEW MODAL
    // ============================================================
    const modal = document.getElementById('idPreviewModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function () {
            const name = document.getElementById('studentCardName').textContent;
            document.getElementById('previewName').innerHTML = name.replace(/,/g, ',<br>');
            document.getElementById('previewId').textContent = document.getElementById('studentCardNo').textContent;
            document.getElementById('previewCourse').textContent = document.getElementById('studentCardCourse').textContent;
            document.getElementById('previewPhoto').src = document.getElementById('photo').src;

            const mainSig = document.getElementById('studentCardSignature');
            const prevSig = document.getElementById('studentCardSignaturePreview');
            if (mainSig && prevSig && mainSig.src) {
                prevSig.src = mainSig.src;
                prevSig.style.display = 'block';
            }

            const qrContainer = document.getElementById('previewQr');
            qrContainer.innerHTML = '';
            if (currentEncryptedId) {
                new QRCode(qrContainer, {
                    text: currentEncryptedId,
                    width: 100,
                    height: 100,
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    }

    // ============================================================
    // 7. CAMERA FUNCTIONS
    // ============================================================
    let video = document.getElementById('webcam');
    let cameraCanvas = document.createElement('canvas');
    let stream = null;

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(s => {
            stream = s;
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('cameraPlaceholder').style.display = 'none';
            document.getElementById('btnStart').style.display = 'none';
            document.getElementById('btnStop').style.display = 'inline-block';
            document.getElementById('btnCapture').style.display = 'inline-block';
        })
        .catch(err => {
            console.error("Camera error:", err);
            alert("Unable to access camera");
        });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        video.style.display = 'none';
        document.getElementById('cameraPlaceholder').style.display = 'flex';
        document.getElementById('btnStart').style.display = 'inline-block';
        document.getElementById('btnStop').style.display = 'none';
        document.getElementById('btnCapture').style.display = 'none';
    }

    function capturePhoto() {
        const photo = document.getElementById('photo');
        cameraCanvas.width = video.videoWidth;
        cameraCanvas.height = video.videoHeight;
        const ctx = cameraCanvas.getContext('2d');
        ctx.drawImage(video, 0, 0, cameraCanvas.width, cameraCanvas.height);
        const imageData = cameraCanvas.toDataURL('image/png');
        photo.src = imageData;
        document.getElementById('studPhoto').value = imageData;
        console.log("Captured image length:", imageData.length);
    }

    // ============================================================
    // 8. PRINT ID CARD FUNCTION
    // ============================================================
    function printFrontIDonly() {
        const front = document.querySelector('.id-frontcard').cloneNode(true);
        const back = document.querySelector('.id-backcard').cloneNode(true);
        const frontBg = "{{ asset('uilibs/images/studentidimage/IDfontframe.webp') }}";
        const printWindow = window.open('', '', 'width=400,height=300');

        printWindow.document.write(`
            <html>
            <head>
                <title>Print ID</title>
                <style>
                    @page { size: 85.6mm 54mm; margin: 0; }
                    body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    .page { width: 85.6mm; height: 54mm; page-break-after: always; display: flex; justify-content: center; align-items: center; }
                    .page:last-child { page-break-after: auto; }
                    .id-frontcard {
                        -webkit-print-color-adjust: exact; print-color-adjust: exact;
                        width: 85.6mm; height: 54mm; border-radius: 14px; overflow: hidden;
                        box-shadow: 0 6px 20px rgba(0,0,0,0.2); display: flex; flex-direction: column;
                        box-sizing: border-box; font-family: 'Poppins', sans-serif;
                        background-image: url("${frontBg}") !important;
                        background-size: cover; background-position: center; background-repeat: no-repeat;
                    }
                    .id-backcard {
                        width: 85.6mm; height: 54mm; border-radius: 14px; overflow: hidden;
                        box-shadow: 0 6px 20px rgba(0,0,0,0.2); display: flex; flex-direction: column;
                        box-sizing: border-box; font-family: 'Poppins', sans-serif;
                    }
                    .id-header { color: white; padding: 2px 10px 10px 10px; }
                    .id-header h6 { margin: 0; font-weight: 700; letter-spacing: 1px; display: flex; align-items: center; gap: 5px; font-size: 10px; }
                    .id-header small { font-size: 8px; opacity: .9; margin-top: -10px; padding-left: 34px; }
                    .id-body { padding: 6px 10px; display: flex; gap: 10px; align-items: center; flex: 1; }
                    .photo-wrapper { margin-top: -30px !important; display: flex; flex-direction: column; align-items: center; }
                    .student-photo { margin-top: -28px; margin-left: 1px; width: 85px; height: 106px; border: 1px solid #eff317; box-shadow: 0 0 0 2px #116e36; border-radius: 6px; overflow: hidden; flex-shrink: 0; }
                    .pic { width: 100%; height: 100%; object-fit: cover; }
                    .signature-icon { font-size: 19px; color: #116e36; }
                    .student-info { margin-top: -17px !important; flex: 1; display: flex; flex-direction: column; justify-content: center; }
                    .student-info h5 { font-weight: 700; color: #0f766e; margin-bottom: 4px; font-size: 12px; }
                    .student-name { margin-left: -5px; background-color: #0a6c3f; padding-left: 8px; padding-top: 2px; padding-bottom: 3px; padding-right: 12px; position: absolute; top: 50px !important; left: 103px; font-size: 12pt; font-weight: bold; color: #ffffff; z-index: 10; clip-path: polygon(100% 0%, 0% 0%, 0% 100%, 92% 100%, 96% 50%, 100% 0%); }
                    .id-label { position: absolute; font-family: "Poppins", sans-serif !important; top: 95px; left: 110px; font-size: 8pt; font-weight: bold; color: #333; }
                    .program-label { position: absolute; font-family: "Poppins", sans-serif !important; top: 120px; left: 110px; font-size: 8pt; font-weight: bold; color: #333; }
                    .student-id { position: absolute; font-weight: bold; font-family: "Poppins", sans-serif !important; top: 105px; left: 110px; font-size: 8pt; }
                    .student-course { position: absolute; font-weight: bold; font-family: "Poppins", sans-serif !important; top: 132px; left: 110px; font-size: 8pt; }
                    .id-footer { height: 18px; }
                    img { max-width: 100%; }
                    .id-body-back { padding: 16px 18px; font-family: "Poppins", sans-serif !important; }
                    .form-labelbold { font-weight: bold !important; }
                    .emergency-text { margin-top: -5px; font-size: 7px; font-style: italic; margin-bottom: 2px; }
                    .back-grid { margin-top: 6px; display: flex; justify-content: space-between; gap: 5px; }
                    .back-col:first-child { flex: 3; margin-top: -5px; }
                    .back-col:last-child { flex: 1; margin-top: -5px; }
                    .back-grid-second { margin-top: 10px; display: flex; justify-content: space-between; gap: 5px; }
                    .back-col-second:first-child { flex: 2; margin-top: -5px; }
                    .back-col-second:nth-child(2) { flex: 1; margin-top: -5px; }
                    .back-col-second:last-child { flex: 1; margin-top: -5px; }
                    .back-col label { font-weight: 300; font-size: 5pt; }
                    .back-col-second label { font-weight: 300; font-size: 5pt; }
                    .back-col-full{ margin-top: -5px !important; }
                    .back-col-full label{ font-weight: 300; font-size: 5pt; }
                    .linedata { padding-top: 2px !important; font-size: 5pt !important; }
                    .line { border-bottom: 1px solid #000; height: 1px; margin-bottom: 8px; font-size: 5pt !important; }
                    .green-line { margin-top: 9px !important; height: 2px; background: #2f855a; margin: 1px 0; }
                    .note-text { margin-top: 5px; text-align: center; line-height: 1.1; font-size: 4.5pt; }
                    .signature-block { text-align: center; margin-top: 5px; }
                    .signature-line { margin-top: 25px !important; border-bottom: 1px solid #000; width: 100px; margin: 0 auto 2px auto; }
                    .signature-name { font-size: 6pt; font-family: 'Poppins', sans-serif; font-weight: 600; line-height: 1.2; }
                    .signature-title { font-size: 5pt; font-family: 'Poppins', sans-serif; margin-top: -1px; }
                    #qrcode { width: 50px; height: 50px; position: absolute; float: right; bottom: 10px !important; right: 10px !important; border: 1px solid #ffffff; }
                </style>
            </head>
            <body>
                <div class="page">${front.outerHTML}</div>
                <div class="page">${back.outerHTML}</div>
            </body>
            </html>
        `);

        printWindow.document.close();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 1000);
    }

    // ============================================================
    // 9. TOPAZ SIGWEB & CANVAS SIGNATURE PAD
    // ============================================================
    (function () {
        'use strict';

        // DOM Elements
        var canvas = document.getElementById('sigwebCanvas');
        var warningEl = document.getElementById('sigwebWarning');
        var errorEl = document.getElementById('sigwebError');
        var hintEl = document.getElementById('sigwebHint');
        var clearBtn = document.getElementById('sigwebClearBtn');
        var saveBtn = document.getElementById('sigwebSaveBtn');
        var spinner = document.getElementById('sigwebSpinner');
        var deviceStatus = document.getElementById('sigwebDeviceStatus');
        var sdkStatus = document.getElementById('sigwebSdkStatus');

        // State
        var ctx = null;
        var isReady = false;
        var isCapturing = false;
        var capturedSignatureData = null;
        var signaturePad = null;
        var lastCanvasData = '';

        function updateStatus(element, text, className) {
            if (!element) return;
            try {
                element.className = 'badge ' + className;
                element.innerHTML = text;
            } catch (e) {}
        }

        function showHide(element, show) {
            if (!element) return;
            try {
                if (show) {
                    element.classList.remove('d-none');
                } else {
                    element.classList.add('d-none');
                }
            } catch (e) {}
        }

        function showError(message) {
            if (errorEl) {
                errorEl.textContent = message;
                showHide(errorEl, true);
            }
        }

        function hideError() {
            if (errorEl) {
                errorEl.textContent = '';
                showHide(errorEl, false);
            }
        }

        // Function to check if canvas has non-white drawing
        function isCanvasDrawn(cnv) {
            if (!cnv) return false;
            try {
                var context = cnv.getContext('2d');
                var imgData = context.getImageData(0, 0, cnv.width, cnv.height).data;
                for (var i = 0; i < imgData.length; i += 4) {
                    if (imgData[i] < 240 || imgData[i+1] < 240 || imgData[i+2] < 240) {
                        return true;
                    }
                }
            } catch (e) {}
            return false;
        }

        // Real-Time Signature Display in both studentCardSignature and studentCardSignaturePreview
        function displaySignatureInPreviews(b64) {
            if (!b64) return;

            // Ensure valid base64 data URL
            if (!b64.startsWith('data:image/')) {
                b64 = 'data:image/png;base64,' + b64;
            }

            // 1. Update hidden input
            var signatureInput = document.getElementById('studSignature');
            if (signatureInput) {
                signatureInput.value = b64;
            }

            // 2. Update Front ID Image: studentCardSignature
            var signaturePreview1 = document.getElementById('studentCardSignature');
            if (signaturePreview1) {
                signaturePreview1.src = b64;
                signaturePreview1.style.display = 'block';
            }

            // 3. Update Preview Modal Image: studentCardSignaturePreview
            var signaturePreview2 = document.getElementById('studentCardSignaturePreview');
            if (signaturePreview2) {
                signaturePreview2.src = b64;
                signaturePreview2.style.display = 'block';
            }

            capturedSignatureData = b64;
        }

        // Sync real time signature from canvas or SignaturePad
        function syncRealtimeFromCanvas() {
            if (!canvas) return;
            try {
                var currentData = null;
                if (signaturePad && !signaturePad.isEmpty()) {
                    currentData = signaturePad.toDataURL('image/png');
                } else if (isCanvasDrawn(canvas)) {
                    currentData = canvas.toDataURL('image/png');
                }

                if (currentData && currentData !== lastCanvasData) {
                    lastCanvasData = currentData;
                    displaySignatureInPreviews(currentData);
                }
            } catch (e) {
                console.warn('[SigWeb] Realtime sync error:', e);
            }
        }

        // Check Topaz SigWeb hardware status
        function checkSigWebStatus() {
            try {
                var serviceUp = false;
                try {
                    serviceUp = (typeof IsSigWebInstalled === 'function') && IsSigWebInstalled();
                } catch (e) {}

                var padConnected = false;
                if (serviceUp && typeof TabletConnectQuery === 'function') {
                    try {
                        var res = TabletConnectQuery();
                        padConnected = !!res && res !== '0' && String(res).toLowerCase() !== 'false';
                    } catch (e) {}
                }

                var status = !serviceUp ? 'service-down' 
                    : (padConnected ? 'pad-connected' : 'pad-disconnected');

                if (status === 'pad-connected') {
                    updateStatus(deviceStatus, '<i class="ti ti-device-tablet"></i> Topaz Connected', 'bg-success');
                    updateStatus(sdkStatus, '<i class="ti ti-code"></i> SigWeb Active', 'bg-success');
                    showHide(warningEl, false);
                    
                    if (!isReady) {
                        isReady = true;
                        setTimeout(function() {
                            startTopazCapture();
                        }, 500);
                    }
                } else if (status === 'service-down') {
                    updateStatus(deviceStatus, '<i class="ti ti-device-tablet"></i> Topaz Offline', 'bg-secondary');
                    updateStatus(sdkStatus, '<i class="ti ti-hand-finger"></i> Canvas Drawing Active', 'bg-info');
                    showHide(warningEl, false);
                    isReady = false;
                    stopTopazCapture();
                } else {
                    updateStatus(deviceStatus, '<i class="ti ti-device-tablet"></i> No Tablet (Canvas Ready)', 'bg-warning');
                    updateStatus(sdkStatus, '<i class="ti ti-code"></i> SigWeb Active', 'bg-success');
                    showHide(warningEl, false);
                    isReady = false;
                    stopTopazCapture();
                }

                return status === 'pad-connected';
            } catch (e) {
                console.error('[SigWeb] Status check error:', e);
                return false;
            }
        }

        function startTopazCapture() {
            try {
                if (isCapturing || !canvas) return;
                ctx = canvas.getContext('2d');
                if (!ctx) return;

                try {
                    if (typeof SetDisplayXSize === 'function') SetDisplayXSize(canvas.width);
                    if (typeof SetDisplayYSize === 'function') SetDisplayYSize(canvas.height);
                    if (typeof SetImageXSize === 'function') SetImageXSize(canvas.width);
                    if (typeof SetImageYSize === 'function') SetImageYSize(canvas.height);
                    if (typeof SetImagePenWidth === 'function') SetImagePenWidth(3);
                    if (typeof SetJustifyMode === 'function') SetJustifyMode(0);
                    if (typeof ClearTablet === 'function') ClearTablet();
                } catch (e) {}

                if (typeof SetTabletState === 'function') {
                    try { SetTabletState(0, 0); } catch (e) {}
                    try {
                        SetTabletState(1, ctx, 20);
                        isCapturing = true;
                    } catch (e) {}
                }
            } catch (e) {}
        }

        function stopTopazCapture() {
            try {
                if (typeof SetTabletState === 'function') {
                    try { SetTabletState(0, 0); } catch (e) {}
                    isCapturing = false;
                }
            } catch (e) {}
        }

        // Capture Button Click Handler
        function handleCapture() {
            try {
                hideError();

                // 1. Try Topaz signature pad if connected and signature has points
                if (isReady && typeof NumberOfTabletPoints === 'function') {
                    NumberOfTabletPoints(function(n) {
                        if (n && n > 0 && typeof GetSigImageB64 === 'function') {
                            GetSigImageB64(function(b64) {
                                if (b64 && b64.length > 50) {
                                    displaySignatureInPreviews(b64);
                                    if (typeof toastr !== 'undefined') {
                                        toastr.success('Signature captured successfully!');
                                    } else {
                                        alert('✅ Signature captured successfully!');
                                    }
                                    return;
                                }
                                checkCanvasSignature();
                            });
                            return;
                        }
                        checkCanvasSignature();
                    });
                } else {
                    checkCanvasSignature();
                }

                function checkCanvasSignature() {
                    var dataUrl = null;
                    if (signaturePad && !signaturePad.isEmpty()) {
                        dataUrl = signaturePad.toDataURL('image/png');
                    } else if (canvas && isCanvasDrawn(canvas)) {
                        dataUrl = canvas.toDataURL('image/png');
                    }

                    if (dataUrl) {
                        displaySignatureInPreviews(dataUrl);
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Signature captured successfully!');
                        } else {
                            alert('✅ Signature captured successfully!');
                        }
                    } else {
                        showError('No signature detected. Please sign on the pad or canvas first.');
                    }
                }
            } catch (e) {
                console.error('[SigWeb] Capture error:', e);
                showError('Failed to capture signature: ' + e.message);
            }
        }

        // Clear Button Handler
        function handleClear() {
            try {
                if (typeof ClearTablet === 'function') {
                    try { ClearTablet(); } catch (e) {}
                }

                if (signaturePad) {
                    signaturePad.clear();
                }

                if (ctx && canvas) {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }

                var signatureInput = document.getElementById('studSignature');
                if (signatureInput) signatureInput.value = '';

                var signaturePreview1 = document.getElementById('studentCardSignature');
                if (signaturePreview1) {
                    signaturePreview1.src = '';
                    signaturePreview1.style.display = 'none';
                }

                var signaturePreview2 = document.getElementById('studentCardSignaturePreview');
                if (signaturePreview2) {
                    signaturePreview2.src = '';
                    signaturePreview2.style.display = 'none';
                }

                capturedSignatureData = null;
                lastCanvasData = '';
                hideError();

                if (isReady) {
                    setTimeout(function() {
                        startTopazCapture();
                    }, 300);
                }
            } catch (e) {
                console.warn('[SigWeb] Clear error:', e);
            }
        }

        // Initialization
        function init() {
            try {
                if (!canvas) return;

                canvas.width = 500;
                canvas.height = 200;
                canvas.style.width = '100%';
                canvas.style.height = '200px';

                ctx = canvas.getContext('2d');
                if (ctx) {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }

                // Initialize SignaturePad for mouse, touch, and stylus drawing
                if (typeof SignaturePad !== 'undefined') {
                    try {
                        signaturePad = new SignaturePad(canvas, {
                            minWidth: 1.5,
                            maxWidth: 3.5,
                            penColor: 'rgb(0, 0, 0)',
                            backgroundColor: 'rgb(255, 255, 255)'
                        });

                        signaturePad.addEventListener('afterUpdate', function() {
                            syncRealtimeFromCanvas();
                        });
                    } catch (e) {
                        console.warn('[SigWeb] SignaturePad init warning:', e);
                    }
                }

                // Canvas drawing event listeners for real-time preview updates
                var isDrawing = false;
                canvas.addEventListener('mousedown', function() { isDrawing = true; });
                canvas.addEventListener('mousemove', function() { if (isDrawing) syncRealtimeFromCanvas(); });
                canvas.addEventListener('mouseup', function() { isDrawing = false; syncRealtimeFromCanvas(); });
                canvas.addEventListener('mouseleave', function() { isDrawing = false; });

                canvas.addEventListener('touchstart', function() { isDrawing = true; }, {passive: true});
                canvas.addEventListener('touchmove', function() { if (isDrawing) syncRealtimeFromCanvas(); }, {passive: true});
                canvas.addEventListener('touchend', function() { isDrawing = false; syncRealtimeFromCanvas(); }, {passive: true});

                // Periodically check canvas for Topaz tablet strokes or background updates
                setInterval(function() {
                    if (isCapturing || (signaturePad && !signaturePad.isEmpty()) || isCanvasDrawn(canvas)) {
                        syncRealtimeFromCanvas();
                    }
                }, 200);

                // Always attach event listeners to buttons
                if (saveBtn) {
                    saveBtn.addEventListener('click', handleCapture);
                }

                if (clearBtn) {
                    clearBtn.addEventListener('click', handleClear);
                }

                // Check SigWeb hardware status if library exists
                if (typeof IsSigWebInstalled === 'function') {
                    checkSigWebStatus();
                    setInterval(function() {
                        try {
                            checkSigWebStatus();
                        } catch (e) {}
                    }, 2000);
                } else {
                    updateStatus(deviceStatus, '<i class="ti ti-device-tablet"></i> Mouse/Touch Active', 'bg-info');
                    updateStatus(sdkStatus, '<i class="ti ti-code"></i> Canvas Mode', 'bg-success');
                }

            } catch (e) {
                console.error('[SigWeb] Init error:', e);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        // Expose for debugging or external triggers
        window.__sigweb = {
            capture: handleCapture,
            clear: handleClear,
            displaySignature: displaySignatureInPreviews,
            syncRealtime: syncRealtimeFromCanvas,
            getSignature: function() { return capturedSignatureData; }
        };

    })();
</script>