@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student List per Subjects
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Student List per Subjects</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>Student List per Subjects</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('listsearch_studsubjectsRead') }}" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control form-control-sm" name="semester">
                                        <option disabled selected>Select</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-12">
                    <div>
                        <table id="attendanceTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Descriptive</th>
                                    <th>Course Yr&Section</th>
                                    <th>No of Stud</th>
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

<script>
    var attendanceReadRoute = "{{ Auth::guard('web')->user()->role == 15 ? route('gradschoolgetlistsearch_studsubjectsRead') : route('getlistsearch_studsubjectsRead') }}";

    var schlyear = "{{ request('schlyear') }}";
    var semester = "{{ request('semester') }}";
    var routeTemplate = "{{ route('studsubjectsReadPDF', ['id' => ':id', 'schlyear' => ':schlyear', 'semester' => ':semester']) }}";
</script>
@endsection
