@extends('layouts.master_kiosk')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 0px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-2 row">
                        <div class="col-md-12 mb-3">
                            <h4 class="card-footer" style="border-radius: 5px">Student ID No.: {{ $studauth->stud_id }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Name: {{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="tab-content" id="vert-tabs-right-tabContent">
                                <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel" aria-labelledby="vert-tabs-right-one-tab">
                                    <div class="card-body table-responsive p-0" style="height: 500px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>School Year</th>
                                                    <th>Semester</th>
                                                    <th>Subject</th>
                                                    <th>Descriptive Title</th>
                                                    <th>Final Grade</th>
                                                    <th>SubjComp</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    function getEquivalentGrade($grade) {
                                                        if ($grade === 'INC') {
                                                            return ['gpa' => 'INC', 'status' => 'Incomplete'];
                                                        } elseif ($grade === 'NN') {
                                                            return ['gpa' => 'NN', 'status' => 'No Name'];
                                                        } elseif ($grade === 'NG') {
                                                            return ['gpa' => 'NG', 'status' => 'No Grade'];
                                                        } elseif ($grade === 'Drp..') {
                                                            return ['gpa' => 'Drp.', 'status' => 'Drop'];
                                                        } elseif ($grade >= 97 || $grade == 1) {
                                                            return ['gpa' => '1.0', 'status' => 'Passed'];
                                                        } elseif ($grade >= 94) {
                                                            return ['gpa' => '1.2', 'status' => 'Passed'];
                                                        } elseif ($grade >= 91) {
                                                            return ['gpa' => '1.5', 'status' => 'Passed'];
                                                        } elseif ($grade >= 88) {
                                                            return ['gpa' => '1.7', 'status' => 'Passed'];
                                                        } elseif ($grade >= 85) {
                                                            return ['gpa' => '2.0', 'status' => 'Passed'];
                                                        } elseif ($grade >= 82) {
                                                            return ['gpa' => '2.2', 'status' => 'Passed'];
                                                        } elseif ($grade >= 79) {
                                                            return ['gpa' => '2.5', 'status' => 'Passed'];
                                                        } elseif ($grade >= 76) {
                                                            return ['gpa' => '2.7', 'status' => 'Passed'];
                                                        } elseif ($grade >= 75) {
                                                            return ['gpa' => '3.0', 'status' => 'Passed'];
                                                        } elseif ($grade >= 70) {
                                                            return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                        } else {
                                                            return ['gpa' => '5.0', 'status' => 'Failure'];
                                                        }
                                                    }

                                                    function displayGrade($grade) {
                                                        if (is_numeric($grade) && strpos($grade, '.') === false) {
                                                            $equivalent = getEquivalentGrade($grade);
                                                            return $equivalent['gpa'];
                                                        }
                                                        return $grade;
                                                    }
                                                    @endphp
                                                @php
                                                    $currentYear = '';
                                                    $currentSemester = '';
                                                    $currentColor = '';
                                                    $colorClasses = ['bg-light', 'bg-secondary'];
                                                    $colorIndex = 0;
                                                @endphp
                                                @foreach($studsub as $datastudsubowner)
                                                    @if($currentYear != $datastudsubowner->schlyear || $currentSemester != $datastudsubowner->semester)
                                                        @php
                                                            $currentYear = $datastudsubowner->schlyear;
                                                            $currentSemester = $datastudsubowner->semester;
                                                            $currentColor = $colorClasses[$colorIndex % count($colorClasses)];
                                                            $colorIndex++;
                                                        @endphp
                                                    @endif
                                                    <tr class="{{ $currentColor }}">
                                                        <td>{{ $datastudsubowner->schlyear }}</td>
                                                        <td>
                                                            @if($datastudsubowner->semester == 1)
                                                                <span class="badge badge-primary">1st Sem</span>
                                                            @elseif($datastudsubowner->semester == 2)
                                                                <span class="badge badge-success">2nd Sem</span>
                                                            @elseif($datastudsubowner->semester == 3)
                                                                <span class="badge badge-secondary">Summer</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $datastudsubowner->sub_name }}</td>
                                                        <td>{{ $datastudsubowner->sub_title }}</td>
                                                        <td><b style="{{ $datastudsubowner->subjFgrade == 'INC' ? 'color: red;' : '' }}">{{ displayGrade($datastudsubowner->subjFgrade) }}</b></td>
                                                        <td><b>{{ displayGrade($datastudsubowner->subjComp) }}</b></td>
                                                        <td>{{ $datastudsubowner->creditEarned }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                                    sdsdsd
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card" style="background-color: #e9ecef !important">
                                <div class="ml-2 mr-2 mt-3 mb-1">
                                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                        
                                    </div>
                                    <div class="mt-3" style="font-size: 13pt;">
                                        <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">View Grades</a>
                                            {{-- <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="true">View Account</a> --}}
                                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
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
@endsection