@extends('layouts.master_ossa')

@section('title')
    CISS V.1.0 || Ossa Student RFID Verification
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
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Ossa</li>
                            <li class="breadcrumb-item active mt-1">Student RFID Verification</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-line-scan"></i> Student RFID Verification
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <form id="adRFIDstud">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="stdntID" class="form-label"> Student ID No.:</label>
                                                    <input type="text" name="stdntid" id="stdntID" class="form-control form-control-sm"
                                                        oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="studentName" class="form-label"> Name:</label>
                                                    <input type="text" id="studentName" class="form-control form-control-sm" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="studentCourse" class="form-label"> Course Year&Section:</label>
                                                    <input type="text" id="studentCourse" class="form-control form-control-sm" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="studentCivilStatus" class="form-label"> Civil Status</label>
                                                    <input type="text" id="studentCivilStatus" class="form-control form-control-sm" readonly>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="studentAddress" class="form-label"> Address:</label>
                                                    <textarea rows="3" id="studentAddress" class="form-control form-control-sm"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="studentUniqueRFID" class="form-label"> RFID:</label>
                                                    <input type="text" id="studentUniqueRFID" name="stdntrfid" class="form-control form-control-sm" readonly>
                                                    <input type="text" id="rfidScanner" style="opacity:0; position:absolute;">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-5 d-none d-md-block">
                                        <div class="table-responsive">
                                            <div class="id-card">
                                                <div class="id-header">
                                                    <div>
                                                        <h6>
                                                            <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="34" style="margin-top: 10px">
                                                            <span>CENTRAL PHILIPPINES STATE UNIVERSITY</span>
                                                        </h6>
                                                        <small style="padding-left: 40px; margin-top: -15px">Kabankalan City, Negros Occidental, 6111</small>
                                                    </div>
                                                </div>
                                                <div class="id-body">
                                                    <div class="student-photo">
                                                        <img id="photo" src="{{ asset('uilibs/images/student.png') }}">
                                                    </div>
                                                    <div class="student-info">
                                                        <h5>STUDENT ID CARD</h5>
                                                        <div class="info-row">
                                                            <div class="info-label">Name</div>
                                                            <div>: <span id="studentCardName"></span></div>
                                                        </div>
                                                        <div class="info-row">
                                                            <div class="info-label">Student ID</div>
                                                            <div>: <span id="studentCardNo"></span></div>
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('rfidScanner').focus();
});
</script>
@endsection
