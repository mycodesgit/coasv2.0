@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
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
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Colleges</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Colleges</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
                                        <table id="collegeProg" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Acronym</th>
                                                    <th>College Name</th>
                                                    <th>Campus</th>
                                                    <th width="10%">Action</th>
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

    <div class="modal fade mt-6" id="editCollegeModal" tabindex="-1" role="dialog" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCollegeModalLabel">Edit College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCollegeForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCollegeId">
                        <div class="form-group mt-3">
                            <label for="editCollegeAbbr">Acronym</label>
                            <input type="text" class="form-control" id="editCollegeAbbr" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editCollegeName">College Name</label>
                            <input type="text" class="form-control" id="editCollegeName" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editCampAbbr">Belongs to:</label>
                            <input type="text" class="form-control" id="editCampAbbr" name="campus" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editCollegeName">Campus</label>
                            <select class="form-control form-control-sm select2" multiple="multiple" name="campus[]">
                                <option value="MC">Main</option>
                                <option value="VC">Victorias</option>
                                <option value="SCC">San Carlos</option>
                                <option value="MP">Moises Padilla</option>
                                <option value="HC">Hinigaran</option>
                                <option value="IC">Ilog</option>
                                <option value="CA">Candoni</option>
                                <option value="CC">Cauayan</option>
                                <option value="SC">Sipalay</option>
                                <option value="HinC">Hinobaan</option>
                                <option value="VE">Valladolid</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var collegeReadRoute = "{{ route('getcollegeRead') }}";
        var collegeUpdateRoute = "{{ route('collegeUpdate', ['id' => ':id']) }}";
        var idEncryptRoute = "{{ route('idcrypt') }}";
    </script>
@endsection
