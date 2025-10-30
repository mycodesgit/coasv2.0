@extends('layouts.master_student')

@section('title')
CISS V.1.0 || Student Class Schedule
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="content-box">
                <div class="col-md-8">
                    <div class="breadcrumb" style="font-size: 13pt">
                        <span>Course: {{ $progAcronym ?? 'Not Available' }} {{ $progCodSuffix ?? 'Not Available' }},</span>
                        <span class="ml-2">School Year: {{ request('schlyear') }},</span>
                        <span class="ml-2">
                            Semester: 
                            @if(request('semester') == 1)
                                1st Sem
                            @elseif(request('semester') == 2)
                                2nd Sem
                            @elseif(request('semester') == 3)
                                Summer
                            @else
                                Unknown Semester
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="breadcrumb">
                        <button type="button" id="refreshSchedule" class="btn btn-primary btn-sm" style="font-size: 9pt">
                            <i class="fas fa-sync"></i> Refresh Schedule
                        </button>
                    </div>
                </div>
                <div class="" id="schedule-grid" style="font-size: 10pt;"></div>
            </div>
        </div>
    </div>

    
@endsection