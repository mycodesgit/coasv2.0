@extends('layouts.master_kiosk')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 0px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <span class="" style="border-radius: 5px; font-size: 15pt;">Student ID No.: {{ $studauth->stud_id }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Name: {{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header text-center">
                    <div class="h3"><b>Student</b> Grades</div>
                </div>
                <div class="card-body">
                    <div class="mt-2 row">
                        <div class="col-md-12">
                            <div class="tab-content" id="vert-tabs-right-tabContent">
                                <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel">
                                    <div class="card-body table-responsive p-0" style="height: 800px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>School Year</th>
                                                    <th>Semester</th>
                                                    <th>Course</th>
                                                    <th>Subject</th>
                                                    <th>Descriptive Title</th>
                                                    <th>Final Grade</th>
                                                    <th>SubjComp</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    if (!function_exists('getEquivalentGPA')) {
                                                        function getEquivalentGPA($grade, $isFourScale) {
                                                            if ($grade === 'INC') return ['gpa' => 'INC', 'status' => 'Incomplete'];
                                                            if ($grade === 'NN') return ['gpa' => 'NN', 'status' => 'No Name'];
                                                            if ($grade === 'NG') return ['gpa' => 'NG', 'status' => 'No Grade'];
                                                            if ($grade === 'Drp.') return ['gpa' => 'Drp.', 'status' => 'Drop'];

                                                            if ($isFourScale) {
                                                                // 4-point scale logic
                                                                if ($grade >= 95 || $grade == 1) return ['gpa' => '1.0', 'status' => 'Passed'];
                                                                if ($grade >= 94) return ['gpa' => '1.1', 'status' => 'Passed'];
                                                                if ($grade >= 93) return ['gpa' => '1.2', 'status' => 'Passed'];
                                                                if ($grade >= 92) return ['gpa' => '1.3', 'status' => 'Passed'];
                                                                if ($grade >= 91) return ['gpa' => '1.4', 'status' => 'Passed'];
                                                                if ($grade >= 90) return ['gpa' => '1.5', 'status' => 'Passed'];
                                                                if ($grade >= 89) return ['gpa' => '1.6', 'status' => 'Passed'];
                                                                if ($grade >= 88) return ['gpa' => '1.7', 'status' => 'Passed'];
                                                                if ($grade >= 87) return ['gpa' => '1.8', 'status' => 'Passed'];
                                                                if ($grade >= 86) return ['gpa' => '1.9', 'status' => 'Passed'];
                                                                if ($grade >= 85 || $grade == 2) return ['gpa' => '2.0', 'status' => 'Passed'];
                                                                if ($grade >= 84) return ['gpa' => '2.1', 'status' => 'Passed'];
                                                                if ($grade >= 83) return ['gpa' => '2.2', 'status' => 'Passed'];
                                                                if ($grade >= 82) return ['gpa' => '2.3', 'status' => 'Passed'];
                                                                if ($grade >= 81) return ['gpa' => '2.4', 'status' => 'Passed'];
                                                                if ($grade >= 80) return ['gpa' => '2.5', 'status' => 'Passed'];
                                                                if ($grade >= 79) return ['gpa' => '2.6', 'status' => 'Passed'];
                                                                if ($grade >= 78) return ['gpa' => '2.7', 'status' => 'Passed'];
                                                                if ($grade >= 77) return ['gpa' => '2.8', 'status' => 'Passed'];
                                                                if ($grade >= 76) return ['gpa' => '2.9', 'status' => 'Passed'];
                                                                if ($grade >= 75 || $grade == 3) return ['gpa' => '3.0', 'status' => 'Passed'];
                                                                if ($grade >= 74) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                if ($grade >= 73) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                if ($grade >= 72) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                if ($grade >= 71) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                if ($grade >= 70) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                return ['gpa' => '5.0', 'status' => 'Failure'];
                                                            } else {
                                                                // Standard GPA logic
                                                                if ($grade >= 97 || $grade == 1) return ['gpa' => '1.00', 'status' => 'Passed'];
                                                                if ($grade >= 94) return ['gpa' => '1.25', 'status' => 'Passed'];
                                                                if ($grade >= 91) return ['gpa' => '1.50', 'status' => 'Passed'];
                                                                if ($grade >= 88) return ['gpa' => '1.75', 'status' => 'Passed'];
                                                                if ($grade >= 85 || $grade == 2) return ['gpa' => '2.00', 'status' => 'Passed'];
                                                                if ($grade >= 82) return ['gpa' => '2.25', 'status' => 'Passed'];
                                                                if ($grade >= 79) return ['gpa' => '2.50', 'status' => 'Passed'];
                                                                if ($grade >= 76) return ['gpa' => '2.75', 'status' => 'Passed'];
                                                                if ($grade >= 75 || $grade == 3) return ['gpa' => '3.00', 'status' => 'Passed'];
                                                                if ($grade >= 70) return ['gpa' => '4.00', 'status' => 'Conditional'];
                                                                return ['gpa' => '5.00', 'status' => 'Failure'];
                                                            }
                                                        } 
                                                            // function getEquivalentGrade($grade) {
                                                            //     if ($grade === 'INC') {
                                                            //         return ['gpa' => 'INC', 'status' => 'Incomplete'];
                                                            //     } elseif ($grade === 'NN') {
                                                            //         return ['gpa' => 'NN', 'status' => 'No Name'];
                                                            //     } elseif ($grade === 'NG') {
                                                            //         return ['gpa' => 'NG', 'status' => 'No Grade'];
                                                            //     } elseif ($grade === 'Drp..') {
                                                            //         return ['gpa' => 'Drp.', 'status' => 'Drop'];
                                                            //     } elseif ($grade >= 97 || $grade == 1) {
                                                            //         return ['gpa' => '1.0', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 94) {
                                                            //         return ['gpa' => '1.2', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 91) {
                                                            //         return ['gpa' => '1.5', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 88) {
                                                            //         return ['gpa' => '1.7', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 85 || $grade == 2) {
                                                            //         return ['gpa' => '2.0', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 82) {
                                                            //         return ['gpa' => '2.2', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 79) {
                                                            //         return ['gpa' => '2.5', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 76) {
                                                            //         return ['gpa' => '2.7', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 75 || $grade == 3) {
                                                            //         return ['gpa' => '3.0', 'status' => 'Passed'];
                                                            //     } elseif ($grade >= 70) {
                                                            //         return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                            //     } else {
                                                            //         return ['gpa' => '5.0', 'status' => 'Failure'];
                                                            //     }
                                                            // }

                                                            // function displayGrade($grade) {
                                                            //     if (is_numeric($grade) && strpos($grade, '.') === false) {
                                                            //         $equivalent = getEquivalentGrade($grade);
                                                            //         return $equivalent['gpa'];
                                                            //     }
                                                            //     return $grade;
                                                            // }
                                                        $isFourScale = Str::contains($studsub->first()->subSec ?? '', '4-');
                                                        
                                                        function displayGrade($grade, $isFourScale = false) {
                                                            if (is_numeric($grade) && strpos($grade, '.') === false) {
                                                                $equivalent = getEquivalentGPA($grade, $isFourScale);
                                                                return $equivalent['gpa'];
                                                            }
                                                            return $grade;
                                                        }
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
                                                        <td>{{ $datastudsubowner->subSec }}</td>
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
                        {{-- <div class="col-md-2">
                            <div class="card" style="background-color: #e9ecef !important">
                                <div class="ml-2 mr-2 mt-3 mb-1">
                                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                        
                                    </div>
                                    <div class="mt-3" style="font-size: 13pt;">
                                        <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">View Grades</a>
                                            <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="true">View Account</a>
                                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection