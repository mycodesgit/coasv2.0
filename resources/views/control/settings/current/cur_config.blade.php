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
                            <li class="breadcrumb-item active mt-1">A.Y. & Semester</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>A.Y. & Semester</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-setconf">
                                            <i class="ti ti-plus"></i> Add New
                                        </button>
                                        @include('modal.settingsconf')
                                        <table id="setconftable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <td>ID</td>
                                                    <th>School Year</th>
                                                    <th>Semester</th>
                                                    <th>Status</th>
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

    <div class="modal fade" id="editSetConfModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="editSetConfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSetConfModalLabel">Edit A.Y. Year & Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSetConfForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editSetConfId">

                        <div class="form-group mt-3">
                            <label for="editSetConfschlyear">School Year: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="schlyear" id="editSetConfschlyear">
                        </div>

                        <div class="form-group mt-3">
                            <label for="editCollegeName">Semester: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="semester" id="editSetConfsemester">
                                <option value="1">First Semester</option>
                                <option value="2">Second Semester</option>
                                <option value="3">Summer</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="editCollegeName">Status: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="set_status" id="editSetConfstatus">
                                <option value="1">Deactivate</option>
                                <option value="2">Activate</option>
                                <option value="3">Upcoming</option>
                                <option value="4">Previous</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var setconfRoute = "{{ route('getsetconfigure') }}";
        var setconfCreateRoute = "{{ route('setconfCreate') }}";
        var setconfUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
    </script>
@endsection
