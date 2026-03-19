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
                            <li class="breadcrumb-item active mt-1">Class Attendance</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Class Attendance Per Subject</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('listsearch_studsubjectsRead') }}" id="enrollStud">
                                            @csrf   

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label>School Year:</label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester:</label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="table-responsive mt-3 p-2">
                                            <table id="attendanceTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Descriptive</th>
                                                        <th>Course Yr&Section</th>
                                                        {{-- <th>No of Stud</th> --}}
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @foreach($substudnow as $datasubstudnow)
                                                        <tr>
                                                            <td>{{ $datasubstudnow->sub_name }}</td>
                                                            <td>{{ $datasubstudnow->sub_title }}</td>
                                                            <td>{{ $datasubstudnow->subSec }}</td>
                                                            <td>{{ $datasubstudnow->countstud }}</td>
                                                            <td>
                                                                <a href="{{ route('listsearchview_studsubjectsRead', ['id' => $datasubstudnow->sid, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td> 
                                                        </tr>
                                                    @endforeach --}}
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
        var attendanceReadRoute = "{{ Auth::guard('web')->user()->role == 15 ? route('gradschoolgetlistsearch_studsubjectsRead') : route('getlistsearch_studsubjectsRead') }}";

        var schlyear = "{{ request('schlyear') }}";
        var semester = "{{ request('semester') }}";
        var routeTemplate = "{{ route('studsubjectsReadPDF', ['id' => ':id', 'schlyear' => ':schlyear', 'semester' => ':semester']) }}";
    </script>
@endsection
