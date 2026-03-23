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
                            <li class="breadcrumb-item active mt-1">Student Scholarship</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Scholarship</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('studscholar_searchRead') }}" id="studscholar">
                                            @csrf

                                            <div class="mt-2">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Academic Year: <span class="">*</span></label>
                                                            <select class="form-control form-control-sm" name="schlyear">
                                                                @foreach($sy as $datasy)
                                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Semester: <span class="">*</span></label>
                                                            <select class="form-control  form-control-sm" name="semester">
                                                                <option disabled selected>---Select---</option>
                                                                <option value="1">First Semester</option>
                                                                <option value="2">Second Semester</option>
                                                                <option value="3">Summer</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-3 p-2">
                                                    <table id="schstud" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>StudID</th>
                                                                <th>Course</th>
                                                                <th>Scholarhip</th>
                                                                <th>Sponsor</th>
                                                                <th>CHEDCategory</th>
                                                                <th>CPSUCategory</th>
                                                                <th>Tuition</th>
                                                                <th>#</th>
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

    <div class="modal fade mt-6" id="editstudSchEnModal" role="dialog" aria-labelledby="editstudSchEnModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editstudSchEnModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editstudSchEnForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editstudSchEnId">
                        <div class="form-group mt-3">
                            <label for="editstudSchEnStudID">Student ID No.</label>
                            <input type="text" id="editstudSchEnStudID" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudSchEnStudName">Student Name</label>
                            <input type="text" id="editstudSchEnStudName" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudSchEnSch">Scholarship</label>
                            <select class="form-control form-control-sm" name="studSch" id="editstudSchEnSch">
                                <option disabled selected>--Select--</option>
                                @foreach($studsch as $datasch)
                                    <option value="{{ $datasch->id }}">{{ $datasch->scholar_name }}</option>
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
        var studschReadRoute = "{{ route('getstudscholarSearchRead') }}";
        var studschUpdateRoute = "{{ route('studscholarUpdate', ['id' => ':id']) }}";
        var idStudSchEncryptRoute = "{{ route('idcrypt') }}";
    </script>
@endsection
