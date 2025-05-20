@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student Record
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
            <li class="breadcrumb-item active mt-1">Student Record</li>
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
                <h4>Student Record</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ Auth::guard('web')->user()->role == 15 ? route('studevalReadgradschool_listsearch') : route('studevalRead_listsearch') }}" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Student ID Number</span></label>
                                    <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
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
                    {{-- <a href="{{ route('studeval.export.excel', ['stud_id' => request('stud_id')]) }}" class="btn btn-success disabled">
                        Export to Excel
                    </a> --}}

                    <div class="card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                                <li class="nav-item ml-1">
                                    <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">Student Record PDF</a>
                                </li>
                                <li class="nav-item ml-1">
                                    <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">Student Record Excel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                                    <iframe src="{{ route('studevalRead_listsearchpdf', ['stud_id' => request('stud_id')]) }}" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">
                                    <div class="table-responsive">
                                        <table id="example3studrecord" class="table table-head-fixed text-nowrap">
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
                                                        function getEquivalentGPA($grade, $isOldSystem) {
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
                                                            
                                                        $isOldSystem = Str::contains($studsub->first()->subSec ?? '', '4-');
                                                        
                                                        function displayGrade($grade, $isOldSystem = false) {
                                                            if (is_numeric($grade) && strpos($grade, '.') === false) {
                                                                $equivalent = getEquivalentGPA($grade, $isOldSystem);
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
                                                    @php
                                                        $entryYear = (int)substr(request('stud_id'), 0, 4);
                                                        $isOldSystem = $entryYear <= 2021;
                                                    @endphp
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
                            </div>
                        </div>
                    </div>
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
