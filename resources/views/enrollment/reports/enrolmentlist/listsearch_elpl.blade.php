@extends('layouts.master_enrollment')

@section('title')
COAS - V2.0 || Student List per Curriculum
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
            <li class="breadcrumb-item active mt-1">Student Enrollment List</li>
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
                <h4>Student Enrollment List</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('elpl_listsearch') }}" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control form-control-sm" name="semester" id="semester">
                                        <option disabled selected>Select</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Course</span></label>
                                    <select class="form-control form-control-sm" name="progCod" id="progCod">
                                        <option disabled selected>Select a course</option>
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

                <div class="col-md-12 mt-3">
                    <table id="elpltable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>StudID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Ext</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Yr.Lvl</th>
                                <th>Subjects</th>
                                <th>Grades</th>
                                <th>Units</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($studelpl as $studentData)
                                @php
                                    $studentPrinted = false;
                                @endphp
                                @foreach($studentData as $subject)
                                    <tr>
                                        @if(!$studentPrinted)
                                            <td>{{ $subject->studentID }}</td>
                                            <td>{{ $subject->lname }}</td>
                                            <td>{{ $subject->fname }}</td>
                                            <td>{{ $subject->mname }}</td>
                                            <td>{{ $subject->ext }}</td>
                                            <td>{{ $subject->gender }}</td>
                                            @php
                                                $studentPrinted = true;
                                            @endphp
                                        @else
                                            <td colspan="6"></td>
                                        @endif
                                        <td>{{ $subject->sub_name }}</td>
                                    </tr>
                                @endforeach
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    var elplReadRoute = "{{ route('elplajax_listsearch') }}";
    var elplclassenrollReadRoute = "{{ route('getCourses') }}";
</script>

@endsection
