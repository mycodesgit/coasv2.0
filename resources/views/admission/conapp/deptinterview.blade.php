@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Examinee Results
@endsection

@section('sideheader')
<h4>Admission</h4>
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
            <li class="breadcrumb-item mt-1">Admission</li>
            <li class="breadcrumb-item mt-1">{{ $applicant->admission_id }}</li>
            <li class="breadcrumb-item mt-1" style="text-transform: uppercase;">{{$applicant->fname}} 
                @if($applicant->mname == null) 
                    @else {{ substr($applicant->mname,0,1) }}.
                @endif {{$applicant->lname}} 
                 
                @if($applicant->ext == 'N/A') 
                    @else{{$applicant->ext}}
                @endif</a></li>
            <li class="breadcrumb-item active mt-1">Assign Results</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>
        <div class="mt-5 row">
            <div class="col-md-10">
                <div class="tab-content" id="vert-tabs-right-tabContent">
                    <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel" aria-labelledby="vert-tabs-right-one-tab">
                        <form method="post" action="{{ route('save_applicant_rating', $applicant->id) }}" enctype="multipart/form-data" id="admissionApply">
                            @csrf
                            @method('PUT')

                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                <h4>Assign Result</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Rating</span></label>
                                        <input type="text" class="form-control form-control-sm" name="rating" value="{{ $applicant->interview->rating }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label><span class="badge badge-secondary">Remarks</span></label>
                                        <select class="form-control form-control-sm" name="remarks">
                                            <option disabled selected>Select</option>
                                            <option value="1" {{ $applicant->interview->remarks == '1' ? 'selected' : '' }}>Accepted for Enrollment</option>
                                            <option value="2" {{ $applicant->interview->remarks == '2' ? 'selected' : '' }}>Not Accepted for Enrollment</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Course</span></label>
                                        <select class="form-control form-control-sm" name="course">
                                            <option disabled selected>Select</option>
                                            @foreach ($program as $programsItem)
                                            <option value="{{ $programsItem->code }}" {{ $programsItem->code == $selectedProgram ? 'selected' : '' }}>{{ $programsItem->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label><span class="badge badge-secondary">Reason</span></label>
                                        <textarea class="form-control" name="reason" rows="3" >{{ $applicant->interview->reason }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5 mb-3">
                                <div class="col-md-12 col-6 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button type="reset" class="btn btn-danger btn-md">
                                        <i class="fas fa-refresh"></i>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Print / Download Data</h4>
                        </div>
                        <iframe src="{{ route('genPreEnrolment', $applicant->id) }}" width="100%" height="800" class="mt-3"></iframe>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-three" role="tabpanel" aria-labelledby="vert-tabs-right-three-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Push to Accepted Applicants</h4>
                        </div>
                        <form method="POST" action="" class="mt-3">
                            @csrf
                            <center>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            @if($applicant->interview->remarks == 1)
                                                <a href="{{ route('save_accepted_applicant', $applicant->id) }}" type="button" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-check"></i> 
                                                </a> <span style="font-size: 20pt" class="mt-2">Push to Accepted Applicant</span>
                                            @elseif($applicant->interview->remarks == 2)
                                                <span style="font-size: 20pt" class="mt-2">Not Accepted for Enrollment</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card" style="background-color: #e9ecef !important">
                    <div class="ml-2 mr-2 mt-3 mb-1">
                        <div class="page-header" style="border-bottom: 1px solid #04401f;">
                            <h4>Menu</h4>
                        </div>
                        <div class="mt-3" style="font-size: 13pt;">
                            <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">Dept. Rating</a>
                                <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="false">Pre-Enrollment</a>
                                <a class="nav-link" id="vert-tabs-right-three-tab" data-toggle="pill" href="#vert-tabs-right-three" role="tab" aria-controls="vert-tabs-right-three" aria-selected="false">Push to Accepted Applicant</a>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')