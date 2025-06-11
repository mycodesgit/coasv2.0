@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student View Grades
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
            <li class="breadcrumb-item mt-1">Reports</li>
            <li class="breadcrumb-item active mt-1">View Student Grades</li>
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
                <h4>View Student Grades</h4>
            </div> 
        </div>

        <div class="row">
            <div class="col-md-4">
                <form method="GET" action="{{ route('search_studviewgradeRead') }}" id="viewstudgrd">
                    {{ csrf_field() }}

                    <div class="form-group mt-2" style="padding: 10px">
                        <div class="form-row">
                            <div class="col-md-8">
                                <label><span class="badge badge-secondary">Student ID Number</span></label>
                                <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                            </div>

                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <div class="form-group mt-2" style="padding: 10px">
                    <div class="form-row">
                        <label>&nbsp;</label>
                        <h6 class="card-footer mt-4" style="border-radius: 5px">Student ID No.: {{ $studauth->stud_id }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Name: {{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                <div class="card-body table-responsive p-0 mt-3" style="height: 500px;">
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
                                function getEquivalentGrade($grade, $isOldSystem) {
                                    if ($grade === 'INC') return ['gpa' => 'INC', 'status' => 'Incomplete'];
                                    if ($grade === 'NN') return ['gpa' => 'NN', 'status' => 'No Name'];
                                    if ($grade === 'NG') return ['gpa' => 'NG', 'status' => 'No Grade'];
                                    if ($grade === 'Drp.') return ['gpa' => 'Drp.', 'status' => 'Drop'];

                                    if ($isOldSystem) {
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

                                function displayGrade($grade, $isOldSystem = false) {
                                    if (is_numeric($grade) && strpos($grade, '.') === false) {
                                        $equivalent = getEquivalentGrade($grade, $isOldSystem);
                                        return $equivalent['gpa'];
                                    }
                                    return $grade;
                                }
                                
                                @endphp
                            @php
                                $stid = request()->get('stud_id');
                                $entryYear = (int)substr($stid, 0, 4);
                                $isOldSystem = $entryYear <= 2021;

                                $currentYear = '';
                                $currentSemester = '';
                                $currentColor = '';
                                $colorClasses = ['bg-light', 'bg-secondary'];
                                $colorIndex = 0;
                            @endphp
                            @foreach($studsubviewgrd as $datastudsubowner)
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
                                    <td><b style="{{ $datastudsubowner->subjFgrade == 'INC' ? 'color: red;' : '' }}">{{ displayGrade($datastudsubowner->subjFgrade, $isOldSystem) }}</b></td>
                                    <td><b>{{ displayGrade($datastudsubowner->subjComp, $isOldSystem) }}</b></td>
                                    <td>{{ $datastudsubowner->creditEarned }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatInput(input) {
        let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');
        
        if (cleaned.length > 0) {
            let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
            input.value = formatted;
        } else {
            input.value = '';
        }
    }

    function handleDelete(event) {
        if (event.key === 'Backspace') {
            let input = event.target;
            let value = input.value;
            input.value = value.substring(0, value.length - 1);
            formatInput(input);
        }
    }
</script>

@endsection
