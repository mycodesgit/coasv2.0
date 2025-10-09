@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Applicant Reports
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
            <li class="breadcrumb-item active mt-1">Applicant Reports</li>
        </ol>

        <div class="page-header">
            <form method="POST" action="{{ route('applicant_reports') }}">
                {{ csrf_field() }}

                <div class="custom-container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Year</span></label>
                                <select class="form-control form-control-sm" id="year" name="year">
                                    @foreach($curryear as $datacurryear)
                                        <option>{{ $datacurryear->adyear }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Campus</span></label>
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
                                        @endif
                                    </option>
                                    @if(Auth::user()->role == 0 || (Auth::user()->campus == 'MC' && Auth::user()->role == 1))
                                        <option value="MC">Main</option>
                                        <option value="VC">Victorias</option>
                                        <option value="SCC">San Carlos</option>
                                        <option value="HC">Hinigaran</option>
                                        <option value="MP">Moises Padilla</option>
                                        <option value="IC">Ilog</option>
                                        <option value="CA">Candoni</option>
                                        <option value="CC">Cauayan</option>
                                        <option value="SC">Sipalay</option>
                                        <option value="HinC">Hinobaan</option>
                                    @else
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Strand</span></label>
                                <select class="form-control  form-control-sm" name="strand">
                                    <option disabled selected>Select</option>
                                    <option value="All">All</option>
                                    <option value="BAM">Accountancy, Business, & Management (BAM)</option>
                                    <option value="GAS">General Academic Strand (GAS)</option>
                                    <option value="HUMSS">Humanities, Education, Social Sciences (HUMSS)</option>
                                    <option value="STEM">Science, Technology, Engineering, & Mathematics (STEM)</option>
                                    <option value="TVL-CHF">TVL - Cookery, Home Economics, & FBS (TVL-CHF)</option>
                                    <option value="TVL-CIV">TVL - CSS, ICT, & VGD (TVL-CIV)</option>
                                    <option value="TVL-AFA">TVL - Agricultural & Fisheries Arts (TVL-AFA)</option>
                                    <option value="TVL-EIM">TVL - Electrical Installation & Maintenance (TVL-EIM)</option>
                                    <option value="TVL-SMAW">TVL - Shielded Metal Arc Welding (TVL-SMAW)</option>
                                    <option value="OLD">Old Curriculum</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('year') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                    </i>
                </small>
            </h5>
        </div>
        <div class="page-header mt-2" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-2">
            <form method="GET" action="{{ route('applicantPDF_reports') }}" id="" target="_blank">
                {{ csrf_field() }}

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-info btn-sm">Generate PDF</button>
                            </div>

                            <div class="col-md-2">
                                <input type="hidden" name="year" value="{{ request('year') }}" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <input type="hidden" name="campus" value="{{ request('campus') }}" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-3">
                                <input type="hidden" name="strand" value="{{ request('strand') }}" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mt-2">
                <table id="appsreplistTable" class="table table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>App ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Strand</th>
                            <th>Email</th>
                            <th>Contact No.</th>
                            <th>Campus</th>
                            <th>Last School Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($data as $applicant)
                            @if ($applicant->p_status != 7)   
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $applicant->admission_id }}</td>
                                <td style="text-transform: uppercase;">{{$applicant->fname}} 
                                    @if($applicant->mname == null) 
                                        @else {{ substr($applicant->mname,0,1) }}.
                                    @endif {{$applicant->lname}}  

                                    @if($applicant->ext == 'N/A') 
                                        @else{{$applicant->ext}}
                                    @endif
                                </td>
                                <td>
                                    @if ($applicant->type == 1) New 
                                        @elseif($applicant->type == 2) Returnee 
                                        @elseif($applicant->type == 3) Transferee 
                                    @endif
                                </td>
                                <td>{{ $applicant->strand }}</td>
                                <td>{{ $applicant->email }}</td>
                                <td>{{ $applicant->contact }}</td>
                                <td>{{ $applicant->campus }}</td>
                                <td>
                                    {{ $applicant->lstsch_attended ?: $applicant->suc_lst_attended }}
                                </td>
                            </tr>
                            @else
                            @endif
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var allApplicantRoute = "{{ route('getapplicantreportsRead') }}";
</script>

@endsection

@section('script')