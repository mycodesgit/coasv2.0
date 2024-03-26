@extends('layouts.master_enrollment')

@section('title')
COAS - V1.0 || Enroll Student
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
            <li class="breadcrumb-item active mt-1">Enroll Student</li>
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
                <div class="row">
                    <div class="col-md-9">
                        <h4>Enroll Student</h4>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-2 mt-2 float-right">
                
            </div> --}}

            <div class="row">
                <div class="col-md-10 scrolling-column">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Last Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">First Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Middle Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Ext. Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                                {{ csrf_field() }}
                                
                                <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>

                                <div class="container">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Course Year&Section</span></label>
                                                <select class="form-control form-control-sm" name="course" id="programNameSelect">
                                                    <option> --Select --</option>
                                                    @foreach ($classEnrolls as $class)
                                                    @php
                                                        $yearsection = preg_replace('/\D/', '', $class->classSection);
                                                    @endphp
                                                    <option value="{{ $class->progAcronym }} {{ $class->classSection }}" data-program-name="{{ $class->progName }}" data-year-section="{{ $class->yearleveldesc }}">
                                                        {{ $class->progAcronym }} {{ $class->classSection }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-9">
                                                <label><span class="badge badge-secondary">Program Name</span></label>
                                                <input type="text" id="programNameInput" name="progName" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Student Level</span></label>
                                                <select class="form-control form-control-sm" name="yrsection">
                                                    <option disabled selected>Select</option>
                                                    @foreach ($studlvl as $data)
                                                    <option value="{{ $data->studLevel }}">{{ $data->studLevel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <label><span class="badge badge-secondary">Year Level</span></label>
                                                <input type="text" id="yearsectionInput" name="progName" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label><span class="badge badge-secondary">Student Level</span></label>
                                                <select class="form-control form-control-sm" name="studscholar">
                                                    <option>NO SCHOLARSHIP</option>
                                                    @foreach ($studscholar as $data)
                                                        <option value="{{ $data->scholar_name }}">{{ $data->scholar_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Major</span></label>
                                                <select class="form-control form-control-sm" name="major">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($mamisub as $mamisubjects)
                                                        <option value="{{ $mamisubjects->submamiID }}">{{ $mamisubjects->submamiName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Minor</span></label>
                                                <select class="form-control form-control-sm" name="minor">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($mamisub as $mamisubjects)
                                                        <option value="{{ $mamisubjects->submamiID }}">{{ $mamisubjects->submamiName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <table id="subjectTable" class="table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Descriptive Title</th>
                                    <th>Credit</th>
                                    <th>Lec Fee</th>
                                    <th>Lab Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Fetched subjects will be displayed here -->
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-2 sticky-column mt-6">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <a href="{{ route('searchStud') }}" class="form-control form-control-sm btn btn-success btn-sm">Enroll New</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" data-toggle="modal" data-target="#modal-addSub">Add Subject</a>
                            <button type="button" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="addSubjectModalBtn" data-toggle="modal" data-target="#modal-studrf">
                                Print RF
                            </button>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Check Conflict</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Est. No. of Stud.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-addSub">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Subjects</label><br>
                            <select class="form-control form-control-sm select2bs4" name="dd" id="subjectSelect">
                                <option disabled selected> --Select-- </option>
                                @foreach($subjOffer as $subs)
                                    <option value="{{ $subs->sub_name }} {{ $subs->subSec }}" 
                                            data-sub-code="{{ $subs->sub_code }}"
                                            data-sub-title="{{ $subs->sub_title }}" 
                                            data-sub-unit="{{ $subs->subUnit }}" 
                                            data-lec-fee="{{ $subs->lecFee }}" 
                                            data-lab-fee="{{ $subs->labFee }}">
                                        {{ $subs->sub_name }} - {{ $subs->subSec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>  
                
                <div class="form-group">
                    <input type="hidden" class="form-control form-control-sm" id="sub_code" readonly>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control form-control-sm" id="sub_title" readonly>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control form-control-sm" id="subUnit" readonly>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control form-control-sm" id="lecFee" readonly>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control form-control-sm" id="labFee" readonly>
                </div>
                
                <button type="button" class="btn btn-primary" id="addSubjectBtn">
                    <i class="fas fa-save"></i> Add
                </button>
            </div>
            <div class="modal-footer justify-content-between">
                <span class="float-right">Total of {{ $subjectCount }} subjects offered this semester</span>
            </div>
        </div>
    </div>
</div>


@include('enrollment.studenroll.modalrf')

<script>
    var fetchTemplateRoute  = "{{ route('fetchSubjects') }}";
    var getfetchSubjectRoute  = "{{ route('coursefetchSubjects') }}";

    document.addEventListener('DOMContentLoaded', function() {
    var scrollableColumn = document.querySelector('.scrolling-column');
        scrollableColumn.addEventListener('scroll', function() {
        var scrollTop = this.scrollTop;
        this.scrollTo({
            top: scrollTop,
            behavior: 'smooth'
            });
        });
    });
</script>

@endsection
