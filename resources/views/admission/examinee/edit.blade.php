@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Applicant Edit
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
            <li class="breadcrumb-item active mt-1">Edit Data</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
        </div>

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
                        <form method="post" action="{{ route('applicant_update', $applicant->id) }}" enctype="multipart/form-data" id="admissionApply">
                            @csrf
                            @method('PUT')
                            <input type="hidden" class="form-control form-control-sm" name="id" value="{{$applicant->id}}" readonly>
                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                <h4>Applicant Information</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Admission No.</span></label>
                                        <input type="text" class="form-control form-control-sm" name="admissionid" value="{{$applicant->admission_id}}" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Admission Type</span></label>
                                        <select class="form-control form-control-sm" name="type">
                                            <option value="">Select</option>
                                            <option value="1" {{ $applicant->type == '1' ? 'selected' : '' }}>New</option>
                                            <option value="2" {{ $applicant->type == '2' ? 'selected' : '' }}>Returnee</option>
                                            <option value="3" {{ $applicant->type == '3' ? 'selected' : '' }}>Transferee</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Preffered Campus</span></label>
                                        <select class="form-control form-control-sm" name="campus">
                                            <option value="{{Auth::user()->campus}}">
                                                @if (Auth::user()->campus == 'MC') Main 
                                                    @elseif(Auth::user()->campus == 'VC') Victorias 
                                                    @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                                    @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                                    @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                                    @elseif(Auth::user()->campus == 'IC') Ilog 
                                                    @elseif(Auth::user()->campus == 'CA') Candoni 
                                                    @elseif(Auth::user()->campus == 'CC') Cauayan 
                                                    @elseif(Auth::user()->campus == 'SC') Sipalay 
                                                    @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                                    @elseif(Auth::user()->campus == 'VE') Valladolid 
                                                @endif
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Lastname</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->lname}}" oninput="this.value = this.value.toUpperCase()"  name="lastname">
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Firstname</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->fname}}" oninput="this.value = this.value.toUpperCase()"  name="firstname">
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Middlename</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->mname}}" oninput="this.value = this.value.toUpperCase()"  name="mname">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Ext.</span></label>
                                        <select class="form-control form-control-sm" name="ext">
                                            <option>N/A</option>
                                            <option value="Jr." {{ $applicant->ext == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                            <option value="Sr." {{ $applicant->ext == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                            <option value="III" {{ $applicant->ext == 'III' ? 'selected' : '' }}>III</option>
                                            <option value="IV" {{ $applicant->ext == 'IV' ? 'selected' : '' }}>IV</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Gender</span></label>
                                        <select class="form-control form-control-sm" name="gender">
                                            <option value="">Select</option>
                                            <option value="Male" {{ $applicant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $applicant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Birthdate</span></label>
                                        <input type="date" class="form-control form-control-sm" value="{{$applicant->bday}}" name="bday" id="bday" onchange="calculateAge()">
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Age</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->age}}" name="age" id="age" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Mobile</span></label>
                                        <input type="number" class="form-control form-control-sm" value="{{$applicant->contact}}" name="contact">
                                    </div>

                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Email Address</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->email}}" placeholder="e.g john@gmail.com" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label><span class="badge badge-secondary">Address</span></label>
                                        <input type="text" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Present Address" name="address" value="{{$applicant->address}}">
                                    </div>
                                </div>
                            </div>

                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Last School Attended</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->lstsch_attended}}" name="lstsch_attended">
                                    </div>

                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Strand</span></label>
                                        <select class="level form-control form-control-sm" name="strand" style="text-transform: uppercase;">
                                            <option disabled selected>Select</option>
                                            @foreach ($strand as $strandItem)
                                            <option value="{{ $strandItem->code }}" {{ $strandItem->code == $selectedStrand ? 'selected' : '' }}>{{ $strandItem->strand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">College/University last attended</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{$applicant->suc_lst_attended}}" name="suc_lst_attended">
                                    </div>

                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Course</span></label>
                                        <select class="form-control form-control-sm" name="course" style="text-transform: uppercase;">
                                            <option value="">Select Course</option>
                                            @foreach ($program as $programsItem)
                                            <option value="{{ $programsItem->code }}" {{ $programsItem->code == $selectedProgram ? 'selected' : '' }}>{{ $programsItem->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Course Preference</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Course Preference 1</span></label>
                                        <select class="form-control form-control-sm" name="preference_1" style="text-transform: uppercase;">
                                            <option disabled selected>Select Course Preference</option>
                                            @foreach ($program as $programsItem)
                                            <option value="{{ $programsItem->code }}" {{ $programsItem->code == $selectedPreference1 ? 'selected' : '' }}>{{ $programsItem->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Course Preference 2</span></label>
                                        <select class="form-control form-control-sm" name="preference_2" style="text-transform: uppercase;">
                                            <option disabled selected>Select Course Preference</option>
                                            @foreach ($program as $programsItem)
                                            <option value="{{ $programsItem->code }}" {{ $programsItem->code == $selectedPreference2 ? 'selected' : '' }}>{{ $programsItem->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Available Documents</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="container-fluid">
                                        <div class="col-md-12">
                                            <input type="radio" name="r_card" value="Yes" @foreach($docs as $doc) {{ old('r_card', $doc->r_card) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="r_card" value="No" @foreach($docs as $doc) {{ old('r_card', $doc->r_card) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Report Card</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" name="g_moral" value="Yes" @foreach($docs as $doc) {{ old('g_moral', $doc->g_moral) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="g_moral" value="No" @foreach($docs as $doc) {{ old('g_moral', $doc->g_moral) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Certificate of Good Moral</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" name="b_cert" value="Yes" @foreach($docs as $doc) {{ old('b_cert', $doc->b_cert) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="b_cert" value="No" @foreach($docs as $doc) {{ old('b_cert', $doc->b_cert) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Birth Certificate</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" name="m_cert" value="Yes" @foreach($docs as $doc) {{ old('m_cert', $doc->m_cert) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="m_cert" value="No" @foreach($docs as $doc) {{ old('m_cert', $doc->m_cert) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Medical Certificate</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" name="t_record" value="Yes" @foreach($docs as $doc) {{ old('t_record', $doc->t_record) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="t_record" value="No" @foreach($docs as $doc) {{ old('t_record', $doc->t_record) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Transcript of Record (For transferees)</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="radio" name="h_dismissal" value="Yes" @foreach($docs as $doc) {{ old('h_dismissal', $doc->h_dismissal) === 'Yes' ? 'checked' : '' }} @endforeach> Yes
                                            <input type="radio" name="h_dismissal" value="No" @foreach($docs as $doc) {{ old('h_dismissal', $doc->h_dismissal) === 'No' ? 'checked' : '' }} @endforeach> No
                                            <label>| Honorable Dismissal (For transferees)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-5 mb-3">
                                <div class="col-md-12 col-6 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-check"></i> Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Uploaded Documents</h4>
                        </div>

                        <div class="d-flex justify-content-center">
                            @foreach($docs as $doc)
                                @if($doc->doc_image)
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-square" src="{{ asset('storage/' . $doc->doc_image) }}" alt="Image">
                                        </div>
                                        <h3 class="profile-username text-center">{{ basename($doc->doc_image) }}</h3>

                                        <div class="col-md-2 mx-auto">
                                            <a href="{{ asset('storage/' . $doc->doc_image) }}" class="btn btn-primary btn-block" data-lightbox="photos">
                                                <b>View Image</b>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    {{-- <p>No image uploaded</p> --}}
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-three" role="tabpanel" aria-labelledby="vert-tabs-right-three-tab">
                        <form method="post" action="{{ route('applicant_schedule_save', $applicant->id) }}" enctype="multipart/form-data" id="admissionAssignSchedule">
                            @csrf

                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                <h4>Schedule Examination</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Date of Admission Test</span></label>
                                        <select class="form-control form-control-md" name="dateID" style="text-transform: uppercase;" onchange="updateDateTime()">
                                            <option disabled selected> ---Select--- </option>
                                            @foreach ($time1 as $dateItem)
                                                <option value="{{ $dateItem->id }}" {{ $dateItem->id == $selectedDate ? 'selected' : '' }}>
                                                    {{ Carbon\Carbon::parse($dateItem->date . ' ' . $dateItem->time)->format('F j Y g:i A') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" id="selectedDate" name="d_admission" class="form-control form-control-md" placeholder="Selected Date">
                                    <input type="hidden" id="selectedTime" name="time" class="form-control form-control-md" placeholder="Selected Time">


                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Venue</span></label>
                                        <select class="form-control form-control-md" name="venue" style="text-transform: uppercase;">
                                            <option disabled selected> ---Select--- </option>
                                            @foreach ($venue1 as $venueItem)
                                                <option value="{{ $venueItem->venue }}" {{ $venueItem->venue == $venue ? 'selected' : '' }}>
                                                    {{ $venueItem->venue }}
                                                </option>
                                            @endforeach
                                        </select>
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

                    <div class="tab-pane fade" id="vert-tabs-right-four" role="tabpanel" aria-labelledby="vert-tabs-right-four-tab">
                        <form method="post" action="{{ route('examinee_result_save_nd', $applicant->id) }}" enctype="multipart/form-data" id="admissionAssignResult">
                            @csrf
                            @method('PUT')

                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                <h4>Assign Result</h4>
                            </div>

                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Raw Score</span></label>
                                        <input type="number" class="form-control" name="raw_score" value="{{$applicant->result->raw_score}}" min="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label><span class="badge badge-secondary">Remarks</span></label>
                                        <select class="form-control" name="percentile">
                                            <option value=""> --Select-- </option>
                                            <option value="Qualified">Qualified</option>
                                            <option value="Failed">Failed</option>
                                        </select>
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

                    <div class="tab-pane fade" id="vert-tabs-right-five" role="tabpanel" aria-labelledby="vert-tabs-right-five-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Print / Download Data</h4>
                        </div>
                        <iframe src="{{ route('applicant_genPDF', $applicant->id) }}" width="100%" height="800" class="mt-3"></iframe>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-six" role="tabpanel" aria-labelledby="vert-tabs-right-six-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Capture Image</h4>
                        </div>
                        <form method="POST" action="{{ route('applicant_save_image', $applicant->id) }}" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label>Camera</label>
                                        <div style="border: 2px solid #04401f !important; width: 325px !important; background-color: #e9ecef !important">
                                            <div id="coas_camera" class="coas_camera"></div>
                                        </div>
                                        <br>
                                        <input type=button class="capture_snapshot btn btn-primary" value="Capture Snapshot" onClick="capture_snapshot()">
                                        <input type="hidden" name="image" class="image-tag">
                                    </div>

                                    <div class="col-md-5">
                                        <label>Result</label>
                                        <div style="border: 2px solid #04401f !important; width: 325px !important; height: 245px;">
                                            <div id="results" class="coas_camera_result"></div>
                                            {{ csrf_field() }}
                                        </div>
                                        <br>
                                        <button class="capture_snapshot btn btn-primary">
                                            <i class="fas fa-check"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="vert-tabs-right-seven" role="tabpanel" aria-labelledby="vert-tabs-right-seven-tab">
                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Push Result</h4>
                        </div>
                        <form method="POST" action="" class="mt-3">
                            @csrf
                            <center>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <a href="{{ route('examinee_confirm', $applicant->id) }}" type="button" class="btn btn-primary btn-lg">
                                                <i class="fas fa-check"></i> 
                                            </a> <span style="font-size: 20pt" class="mt-2">Push to Result</span>
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
                                <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">Information</a>
                                <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="false">Uploaded Docs</a>
                                <a class="nav-link" id="vert-tabs-right-three-tab" data-toggle="pill" href="#vert-tabs-right-three" role="tab" aria-controls="vert-tabs-right-three" aria-selected="false">Schedule</a>
                                <a class="nav-link" id="vert-tabs-right-four-tab" data-toggle="pill" href="#vert-tabs-right-four" role="tab" aria-controls="vert-tabs-right-four" aria-selected="false">Test Result</a>
                                <a class="nav-link" id="vert-tabs-right-five-tab" data-toggle="pill" href="#vert-tabs-right-five" role="tab" aria-controls="vert-tabs-right-five" aria-selected="false">View Print</a>
                                <a class="nav-link" id="vert-tabs-right-six-tab" data-toggle="pill" href="#vert-tabs-right-six" role="tab" aria-controls="vert-tabs-right-six" aria-selected="false">Capture Image</a>
                                <a class="nav-link" id="vert-tabs-right-seven-tab" data-toggle="pill" href="#vert-tabs-right-seven" role="tab" aria-controls="vert-tabs-right-seven" aria-selected="false">Push to Result</a>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('js/webcam/webcam.min.js')}}"></script>   
<script language="JavaScript">
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#coas_camera');

    function capture_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        });
    }
</script>
<script>
    function calculateAge() {
        var birthday = document.getElementById('bday').value;
        var today = new Date();
        var birthDate = new Date(birthday);
        var age = today.getFullYear() - birthDate.getFullYear();

        if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
            age--;
        }

        document.getElementById('age').value = age;
    }
</script>

<script>
    var assignSchedRoute = "{{ route('applicant_schedule_save', $applicant->id) }}";
    var assignResultRoute = "{{ route('examinee_result_save_nd', $applicant->id) }}";
</script>

@endsection
