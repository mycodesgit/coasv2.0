@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Transfered Student</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Transfered Student</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive mt-3 p-2">
                                            <button type="button" class="btn btn-success btn-sm mb-4" data-bs-toggle="modal" data-bs-target="#modal-studenttrans">
                                                <i class="fas fa-plus"></i> Add New
                                            </button>

                                            @include('modal.studenttransferAdd')

                                            <table id="liststudtrans" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Stud ID</th>
                                                        <th>Campus</th>
                                                        <th>Date Transfered</th>
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
    </div>

    <div class="modal fade mt-6" id="editStudTransferModal" tabindex="-1" role="dialog" aria-labelledby="editStudTransferModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudTransferModalLabel">Edit Transfer Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudTransferForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editStudTransferId">
                        <div class="form-group mb-2">
                            <label for="editStudTransferidcardno">Student ID</label>
                            <input type="text" class="form-control" id="editStudTransferidcardno" name="stud_id">
                        </div>
                        <div class="form-group mb-2">
                            <label for="editStudTransferName">Name</label>
                            <input type="text" class="form-control" id="editStudTransferName" name="">
                        </div>
                        <div class="form-group mb-2">
                            <label for="editStudTransferfromcampus">From Campus</label>
                            <input type="text" class="form-control" id="editStudTransferfromcampus" name="fromcampus" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{-- <button type="submit" class="btn btn-primary">Save changes</button> --}}
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
                            document.getElementById('primaryId').value = '';
                            document.getElementById('fromCampus').value = '';
                        } else {
                            const fullName = `${data.lname}, ${data.fname} ${data.mname || ''}`.trim();
                            document.getElementById('studentName').value = fullName.toUpperCase();
                            document.getElementById('primaryId').value = data.id || ''; 
                            document.getElementById('fromCampus').value = data.campus || ''; 
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching student:', error);
                        document.getElementById('studentName').value = 'Error fetching student';
                        document.getElementById('primaryId').value = '';
                        document.getElementById('fromCampus').value = '';
                    });
            } else {
                document.getElementById('studentName').value = '';
                document.getElementById('primaryId').value = '';
                document.getElementById('fromCampus').value = '';
            }
        }

        var studtransferCreateRoute = "{{ route('studtransferCreate') }}";
        var studtransferReadRoute = "{{ route('getstudentTransferRead') }}";
    </script>
@endsection
