@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || List if Faculty
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">List if Faculty</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>List of Faculty</h4>
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('facultyCreate') }}" id="adFac">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add Faculty</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Belongs to</span></label>
                                        <select class="form-control form-control-sm" name="dept">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($collegelist as $datacollegelist)
                                                <option value="{{ $datacollegelist->college_abbr }}">{{ $datacollegelist->college_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Lastname</span></label>
                                        <input type="text" name="lname" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Firstname</span></label>
                                        <input type="text" name="fname" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Middle initial</span></label>
                                        <input type="text" name="mname" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Ext</span></label>
                                        <input type="text" name="ext" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Salutation</span></label>
                                        <select class="form-control form-control-sm" name="adrID">
                                            <option disabled selected> --Select-- </option>
                                            @foreach($adr as $dataadr)
                                                <option value="{{ $dataadr->id }}">{{ $dataadr->adrDesc }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table id="facltyTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Salutation</th>
                            <th>College</th>
                            <th>Campus</th>
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

<div class="modal fade" id="editFacultyModal" role="dialog" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFacultyForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editFacultyId">
                    <div class="form-group">
                        <label for="editdept">College</label>
                        <select id="college_room" class="form-control form-control-sm" id="editdept" name="dept">
                            <option disabled selected> ---Select---</option>
                            @foreach($collegelist as $datacollegelist)
                                <option value="{{ $datacollegelist->college_abbr }}">{{ $datacollegelist->college_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editLastname">Lastname</label>
                        <input type="text" class="form-control form-control-sm" id="editLastname" name="lname">
                    </div>
                    <div class="form-group">
                        <label for="editFirstname">Firstname</label>
                        <input type="text" class="form-control form-control-sm" id="editFirstname" name="fname">
                    </div>
                    <div class="form-group">
                        <label for="editMiddlename">Middlename</label>
                        <input type="text" class="form-control form-control-sm" id="editMiddlename" name="mname">
                    </div>
                    <div class="form-group">
                        <label for="editExtname">Ext</label>
                        <input type="number" class="form-control form-control-sm" id="editExtname" name="ext">
                    </div>
                    <div class="form-group">
                        <label for="editExtname">Salutation</label>
                        <select class="form-control form-control-sm" id="editSalutation" name="adrID">
                            <option disabled selected> --Select-- </option>
                            @foreach($adr as $dataadr)
                                <option value="{{ $dataadr->id }}">{{ $dataadr->adrDesc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var facultyReadRoute = "{{ route('getfacultylistRead') }}";
    var facultyCreateRoute = "{{ route('facultyCreate') }}";
    var facultyUpdateRoute = "{{ route('facultyUpdate', ['id' => ':id']) }}";
    var facultyDeleteRoute = "{{ route('facultyDelete', ['id' => ':id']) }}";
    var roomidEncryptRoute = "{{ route('idcrypt') }}";
</script>

@endsection
