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
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
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
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">CHED Scholarship</h1>
                        <p class="text-muted small mb-0">Manage CHED Scholarship.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> List of CHED Scholarship
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-3">
                                    <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-chedsch">
                                        <i class="fas fa-plus"></i> Add New
                                    </button>

                                    <table id="chedschtable" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Scholarship Category</th>
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

    @include('modal.chedschAdd')

    <div class="modal fade mt-6" id="editCHEDSchModal" role="dialog" aria-labelledby="editCHEDSchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCHEDSchModalLabel"><i class="ti ti-pencil"></i> Edit Scholarship</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCHEDSchForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCHEDSchId">
                        <div class="form-group">
                            <label class="form-label fw-semibold" for="editCHEDSchName">Scholarship: <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="chedsch_name" id="editCHEDSchName" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="button" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var chedschcatReadRoute = "{{ route('getchedscholarlist') }}";
        var chedschcatCreateRoute = "{{ route('chedscholarCreate') }}";
        var chedschcatUpdateRoute = "{{ route('chedscholarUpdate', ['id' => ':id']) }}";
        var chedschcatDeleteRoute = "{{ route('chedscholarDelete', ['id' => ':id']) }}";
        var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
    </script>
@endsection
