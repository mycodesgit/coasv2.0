@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
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
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">Signatories</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Signatories</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex mt-3" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-one" type="button" role="tab"
                                                    aria-controls="pills-one" aria-selected="true">
                                                    President & Vice Signatories
                                                </button>
                                            </li>
                                            &nbsp;
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-two" type="button" role="tab"
                                                    aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                                    Gradesheet Signatories
                                                </button>
                                            </li>
                                            &nbsp;
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-three-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-three" type="button" role="tab"
                                                    aria-controls="pills-three" aria-selected="false" tabindex="-1">
                                                    Guidance Counselors Signatories
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="tab-content mt-1" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                                <div class="table-responsive">
                                                    <table id="city" class="table table-hover table-striped" style="width: 100%">  
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
                                            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                                <div class="table-responsive">
                                                    <table id="city" class="table table-hover table-striped" style="width: 100%">  
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
                                            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab" tabindex="0">
                                                <button type="button" class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modal-guidanceSignatory">
                                                    <i class="fas fa-plus"></i> Add New
                                                </button>
                                                @include('modal.guidancecounsilorAdd')
                                                <div class="table-responsive">
                                                    <table id="signatoryguidanceTable" class="table table-hover table-striped" style="width: 100%">  
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
        var sigpnatoriesCreateRoute = "{{ route('signatory.create') }}";
        var sigpnatoriesReadRoute = "{{ route('signatory.show') }}";
    </script>
@endsection
