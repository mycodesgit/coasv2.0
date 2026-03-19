@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Subjects</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Subjects</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="table-responsive p-2">
                                            <button type="button" class="btn btn-success btn-sm mb-4" data-bs-toggle="modal" data-bs-target="#modal-subjects">
                                                <i class="fas fa-plus"></i> Add New
                                            </button>

                                            @include('modal.subjectsAdd')

                                            <table id="listsub" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Subject Code</th>
                                                        <th>Subject Name</th>
                                                        <th>Description</th>
                                                        <th>Unit</th>
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

    <script>
        var subjectReadRoute = "{{ route('getsubjectsRead') }}";
        var subjectCreateRoute = "{{ route('subjectsCreate') }}";
        var subjectCodeRoute = "{{ route('getNextSubjectNumber') }}";
    </script>
@endsection
