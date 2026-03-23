@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || Scholarship
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
                            <li class="breadcrumb-item mt-1">Scholarship</li>
                            <li class="breadcrumb-item active mt-1">CHED Scholarship</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>CHED Scholarship</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-unisch">
                                            <i class="fas fa-plus"></i> Add New
                                        </button>
                                        @include('modal.unischAdd')
                                        <table id="unischtable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Scholarship Category</th>
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

    <div class="modal fade mt-6" id="editUNISchModal" role="dialog" aria-labelledby="editUNISchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUNISchModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUNISchForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUNISchId">
                        <div class="form-group">
                            <label for="editUNISchName">Scholarship <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="unisch_name" id="editUNISchName" rows="3"></textarea>
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
        var unischcatReadRoute = "{{ route('getunischolarlist') }}";
        var unischcatCreateRoute = "{{ route('unischolarCreate') }}";
        var unischcatUpdateRoute = "{{ route('unischolarUpdate', ['id' => ':id']) }}";
        var unischcatDeleteRoute = "{{ route('unischolarDelete', ['id' => ':id']) }}";
        var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
    </script>
@endsection
