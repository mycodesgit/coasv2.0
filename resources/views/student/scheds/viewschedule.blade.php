@extends('layouts.master_student')

@section('title')
CISS V.1.0 || Student Grades
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="content-box">
                <div class="table-responsive">
                    <table id="ss" class="table table-striped">
                        <thead>
                            <tr>
                                <th>A.Y. Semester</th>
                                <th>Course Yr&Section</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollmentHistory as $history)
                                <tr>
                                    <td>{{ $history->schlyear }}
                                        @if($history->semester == 1)
                                            <span class="badge badge-primary">1st Sem</span>
                                        @elseif($history->semester == 2)
                                            <span class="badge badge-success">2nd Sem</span>
                                        @elseif($history->semester == 3)
                                            <span class="badge badge-secondary">Summer</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->progAcronym }} {{ $history->studYear }}-{{ $history->studSec }}</td>
                                    <td>
                                        <a href="{{ route('schedstudentclassShow', ['schlyear' => $history->schlyear, 'semester' => $history->semester, 'progCod' => $history->progCod.'+'.$history->studYear.'-'.$history->studSec]) }}" class="btn btn-outline-success btn-xs">
                                            View Sched
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection