@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Signatory Settings
@endsection

@section('sideheader')
<h4>Settings</h4>
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
            <li class="breadcrumb-item mt-1">Settings</li>
            <li class="breadcrumb-item active mt-1">Signatory Settings</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>  
            @endif
        </p>

        <div class="row">
            <div class="col-md-10">
                <div class="tab-content" id="vert-tabs-right-tabContent">
                    <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel" aria-labelledby="vert-tabs-right-one-tab">
                        <div class="page-header" style="border-bottom: 1px solid #04401f;">
                            <button type="button" class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modal-region">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        <div class="mt-3">
                            <table id="regions" class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Campus</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                        <div class="page-header" style="border-bottom: 1px solid #04401f;">
                            <button type="button" class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modal-presvice">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        @include('modal.presviceAdd')
                        <div class="mt-3">
                            <table id="presvicetab" class="table table-hover table-striped" style="width: 100%">  
                                <thead>
                                    <tr>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-three" role="tabpanel" aria-labelledby="vert-tabs-right-three-tab">
                        <div class="page-header" style="border-bottom: 1px solid #04401f;">
                            <button type="button" class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modal-city">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>
                        <div class="mt-3">
                            <table id="city" class="table table-hover table-striped" style="width: 100%">  
                                <thead>
                                    <tr>
                                        <th>City ID</th>
                                        <th>Region</th>
                                        <th>Province</th>
                                        <th>City Name</th>
                                        <th>ZipCode</th>
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
            <div class="col-md-2 mt-3">
                <div class="card" style="background-color: #e9ecef !important">
                    <div class="ml-2 mr-2 mt-1 mb-1">
                        <div class="mt-1" style="font-size: 13pt;">
                            <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">Gradesheet</a>
                                <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="true">Pres&Vice</a>         
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editRegionModal" tabindex="-1" role="dialog" aria-labelledby="editRegionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRegionModalLabel">Edit Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRegionForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editRegionId">
                    <div class="form-group">
                        <label for="editRegionName">Region Name</label>
                        <input type="text" class="form-control" id="editRegionName" name="name">
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
    var sigpresviceCreateRoute = "{{ route('presViceSigCreate') }}";
    var sigpresviceReadRoute = "{{ route('getPresViceSigRead') }}";
    var allcityUpdateRoute = "{{ route('cityUpdate', ['id' => ':id']) }}";
</script>

@endsection
