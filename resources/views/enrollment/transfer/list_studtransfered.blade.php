@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Transfered Students
@endsection

@section('sideheader')
<h4>Enrollment</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Transfered Students</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Transfered Students</h4>
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="mt-1 row">
            <div class="col-md-12">
                <button type="button" class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#modal-studenttrans">
                    <i class="fas fa-plus"></i> Add New
                </button>

                @include('modal.studenttransferAdd')

                <table id="listsub" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Stud ID</th>
                            <th>Campus</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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
            const url = `{{ route('getStudentById', ['id' => ':id']) }}`.replace(':id', studid);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // If there's an error, clear all fields and show a message
                        document.getElementById('studentName').value = 'Student not found';
                        document.getElementById('primaryId').value = '';
                        document.getElementById('fromCampus').value = '';
                    } else {
                        // Construct the full name
                        const fullName = `${data.lname}, ${data.fname} ${data.mname || ''}`.trim();
                        // Populate the fields
                        document.getElementById('studentName').value = fullName.toUpperCase();
                        document.getElementById('primaryId').value = data.id || ''; // Use the correct key
                        document.getElementById('fromCampus').value = data.campus || ''; // Use the correct key
                    }
                })
                .catch(error => {
                    console.error('Error fetching student:', error);
                    // Clear fields on error
                    document.getElementById('studentName').value = 'Error fetching student';
                    document.getElementById('primaryId').value = '';
                    document.getElementById('fromCampus').value = '';
                });
        } else {
            // Clear fields if input is empty
            document.getElementById('studentName').value = '';
            document.getElementById('primaryId').value = '';
            document.getElementById('fromCampus').value = '';
        }
    }

    var studtransferCreateRoute = "{{ route('studtransferCreate') }}";
</script>

@endsection

@section('script')