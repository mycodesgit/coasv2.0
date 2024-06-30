@extends('layouts.master_adminkiosk')

@section('title')
CISS V.1.0 || Kiosk User
@endsection

@section('sideheader')
<h4>Kiosk Admin</h4>
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
            <li class="breadcrumb-item mt-1">Kiosk Admin</li>
            <li class="breadcrumb-item active mt-1">Kiosk User</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="page-header mt-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#modal-kioskuser">
                    <i class="fas fa-user-plus"></i> Add New
                </button>

                @include('modal.kioskuserAdd')

                <table id="kioskuser" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student ID No.</th>
                            <th>Lastname</th>
                            <th>Firstname</th>
                            <th>Middle Initial</th>
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

<div class="modal fade" id="editKioskUserModal" tabindex="-1" role="dialog" aria-labelledby="editKioskUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKioskUserModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editKioskUserForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editKioskUserId">
                    <div class="form-group">
                        <label for="editKioskStudID">Student ID Number</label>
                        <input type="text" class="form-control" id="editKioskStudID" name="studid" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="editpasswordInput">Password</label>
                        <input type="text" class="form-control" name="password" id="editpasswordInput" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="editgeneratePassword" class="btn btn-success">
                        <i class="fas fa-key"></i> Generate Pass
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
</script>

<script>
    var kioskuserReadRoute = "{{ route('getadminkioskRead') }}";
    var kioskuserCreateRoute = "{{ route('adminkioskCreate') }}";
    var kioskuserUpdateRoute = "{{ route('adminkioskUpdate', ['id' => ':studkiosid']) }}";
    var kioskuserDeleteRoute = "{{ route('adminkioskDelete', ['id' => ':id']) }}";
</script>

@endsection
