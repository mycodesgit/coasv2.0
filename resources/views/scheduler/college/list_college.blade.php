@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || College
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
            <li class="breadcrumb-item active mt-1">College</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>College</h4>
        </div>

        <div class="mt-3">
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

<div class="modal fade" id="editCollegeModal" tabindex="-1" role="dialog" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCollegeModalLabel">Edit College</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCollegeForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCollegeId">
                    <div class="form-group">
                        <label for="editCollegeAbbr">Acronym</label>
                        <input type="text" class="form-control" id="editCollegeAbbr" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editCollegeName">College Name</label>
                        <input type="text" class="form-control" id="editCollegeName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editCampAbbr">Belongs to:</label>
                        <input type="text" class="form-control" id="editCampAbbr" name="campus" readonly>
                    </div>
                    <div class="form-group">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
