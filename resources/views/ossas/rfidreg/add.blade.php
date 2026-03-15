@extends('layouts.master_ossa')

@section('title')
    CISS V.1.0 || Ossa Student RFID Registration
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Ossa</li>
                            <li class="breadcrumb-item active mt-1">Student RFID Registration</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-grid-scan"></i> Student RFID Registration
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="row g-5">
                                            <div class="col-lg-6">
                                                <label for="stdntID" class="form-label"> Student ID No.:</label>
                                                <input type="text" name="studid" id="stdntID" class="form-control" oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);" autofocus>

                                            </div>
                                            <div class="col-lg-6">
                                                <label for="studentName" class="form-label"> Name:</label>
                                                <input type="text" id="studentName" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="studentCourse" class="form-label"> Course Year&Section:</label>
                                                <input type="text" id="studentCourse" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="studentCivilStatus" class="form-label"> Civil Status</label>
                                                <input type="text" id="studentCivilStatus" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="studentAddress" class="form-label"> Address:</label>
                                                <textarea rows="3" id="studentAddress" class="form-control"></textarea>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="studentUniqueRFID" class="form-label"> RFID:</label>
                                                <input type="text" id="studentUniqueRFID" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="table-responsive">
                                            <div class="id-card">
                                                <div class="id-header">
                                                    <div>
                                                        <h5>
                                                            <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="34">
                                                            CENTRAL PHILIPPINES STATE UNIVERSITY
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="id-body">
                                                    <div class="student-photo">
                                                        <img id="photo" src="{{ asset('uilibs/images/user.png') }}">
                                                    </div>
                                                    <div class="student-info">
                                                        <h5>STUDENT ID CARD</h5>
                                                        <div class="info-row">
                                                            <div class="info-label">Name</div>
                                                            <div>: <span id="studentCardName"></span></div>
                                                        </div>
                                                        <div class="info-row">
                                                            <div class="info-label">Student ID</div>
                                                            <div>: <span id="studentCardNo">2018-000-</span></div>
                                                        </div>
                                                        <div class="info-row">
                                                            <div class="info-label">Course</div>
                                                            <div>: <span id="studentCardCourse"></span></div>
                                                        </div>
                                                        <div class="info-row">
                                                            <div class="info-label">Gender</div>
                                                            <div>: <span id="studentCardGender"></span></div>
                                                        </div>
                                                        <div class="barcode"></div>
                                                    </div>
                                                </div>

                                                <div class="id-footer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
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

        function fetchStudentName(studid) {
            if (studid) {

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
                        document.getElementById('studentCardGender').textContent = '';

                    } else {

                        const fullName = `${data.lname}, ${data.fname} ${data.mname}`.toUpperCase();
                        const civilStatus = `${data.civil_status}`.toUpperCase();
                        const progAcronym = `${data.progAcronym}`.toUpperCase();
                        const address = `${data.address}`.toUpperCase();

                        // INPUT FIELD
                        document.getElementById('studentName').value = fullName;
                        document.getElementById('studentCourse').value = progAcronym;
                        document.getElementById('studentCivilStatus').value = civilStatus;
                        document.getElementById('studentAddress').value = address;

                        // ID CARD PREVIEW
                        document.getElementById('studentCardName').textContent = fullName;
                        document.getElementById('studentCardNo').textContent = data.stud_id;
                        document.getElementById('studentCardCourse').textContent = data.progAcronym;
                        document.getElementById('studentCardGender').textContent = data.gender;

                    }

                })
                // .catch(error => {
                //     console.error('Error fetching student:', error);
                // });

            }

        }
    </script>
@endsection
