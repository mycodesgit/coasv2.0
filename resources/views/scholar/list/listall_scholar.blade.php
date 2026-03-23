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
                            <li class="breadcrumb-item active mt-1">All Scholarship</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>All Scholarship</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-allsch">
                                            <i class="fas fa-plus"></i> Add New
                                        </button>
                                        @include('modal.allschAdd')
                                        <table id="allschTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Scholarship Name</th>
                                                    <th width="20%">Sponsor</th>
                                                    <th>CHED Cat</th>
                                                    <th>CPSU Cat</th>
                                                    <th>FS</th>
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

    <div class="modal fade mt-6" id="editAllSchModal" role="dialog" aria-labelledby="editAllSchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAllSchModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAllSchForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editSchChoiceId">
                        <div class="form-group mt-3">
                            <label for="editSchChoiceName">Scholarship</label>
                            <input type="text" class="form-control form-control-sm" name="scholar_name" id="editSchChoiceName">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSchSponChoiceName">Scholarship</label>
                            <textarea class="form-control form-control-sm" name="scholar_sponsor" id="editSchSponChoiceName" rows="3"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSchchedChoiceName">CHED Scholarship Category</label>
                            <select class="form-control form-control-sm" name="chedcategory" id="edichedChoiceName">
                                <option disabled selected>--Select--</option>
                                @foreach($ched as $datached)
                                    <option value="{{ $datached->id }}">{{ $datached->chedsch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSchuniChoiceName">CPSU Scholarship Category</label>
                            <select class="form-control form-control-sm" name="unicategory" id="ediuniChoiceName">
                                <option disabled selected>--Select--</option>
                                @foreach($uni as $datauni)
                                    <option value="{{ $datauni->id }}">{{ $datauni->unisch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSchfsChoiceName">Funding Source</label>
                            <select class="form-control form-control-sm" name="fund_source" id="edifsChoiceName">
                                <option disabled selected>--Select--</option>
                                @foreach($fs as $datafs)
                                    <option value="{{ $datafs->id }}">{{ $datafs->fndsource_name }}</option>
                                @endforeach
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
        var allschcatReadRoute = "{{ route('getallscholarlist') }}";
        var allschcatCreateRoute = "{{ route('allscholarCreate') }}";
        var allschcatUpdateRoute = "{{ route('allscholarUpdate', ['id' => ':id']) }}";
        var idSchEncryptRoute = "{{ route('idcrypt') }}";
        var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
    </script>
@endsection
