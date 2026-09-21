@extends('layouts.master_adminkiosk')

@section('title')
CISS V.1.0 || Kiosk Admin
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Kiosk</li>
                            <li class="breadcrumb-item active mt-1">Kiosk User</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Kiosk User Account</h1>
                        <p class="text-muted small mb-0">Overview of daily transaction logs for registered students only.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> List of registered students in Kiosk
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive p-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-kioskuser">
                                            <i class="fas fa-user-plus"></i> Add New
                                        </button>

                                        @include('modal.kioskuserAdd')
                                        <table id="kioskuser" class="table table-striped" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Student ID No.</th>
                                                    <th>Lastname</th>
                                                    <th>Firstname</th>
                                                    <th>Middle Initial</th>
                                                    <th>No. of Reset</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editKioskUserModal" tabindex="-1" role="dialog" aria-labelledby="editKioskUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKioskUserModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKioskUserForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editKioskUserId">
                        <div class="form-group">
                            <label for="editKioskStudID">Student ID Number</label>
                            <input type="text" class="form-control" id="editKioskStudID" name="studid" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                        </div>
                        <div class="form-group mt-6">
                            <label for="editpasswordInput">Password</label>
                            <input type="text" class="form-control" name="password" id="editpasswordInput" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="editgeneratePassword" class="btn btn-outline-warning">
                            <i class="fas fa-key"></i> Generate Pass
                        </button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
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
                const url = `{{ route('getStudentById', ['id' => ':id']) }}`.replace(':id', studid);
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('studentName').value = 'Student not found';
                        } else {
                            const fullName = `${data.lname}, ${data.fname} ${data.mname}`;
                            document.getElementById('studentName').value = fullName.toUpperCase();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching student:', error);
                        document.getElementById('studentName').value = 'Error fetching student';
                    });
            } else {
                document.getElementById('studentName').value = '';
            }
        }
    </script>

    <script>
        var kioskuserReadRoute = "{{ route('getadminkioskRead') }}";
        var kioskuserCreateRoute = "{{ route('adminkioskCreate') }}";
        var kioskuserUpdateRoute = "{{ route('adminkioskUpdate', ['id' => ':studkiosid']) }}";
        var kioskuserDeleteRoute = "{{ route('adminkioskDelete', ['id' => ':id']) }}";
    </script>

@endsection
