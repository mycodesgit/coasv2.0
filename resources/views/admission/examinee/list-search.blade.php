@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Examinee Search List
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
            <li class="breadcrumb-item active mt-1">Examinee Search List</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('srchexamineeList') }}">
                {{ csrf_field() }}

                <div class="custom-container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Year</span></label>
                                <select class="form-control form-control-sm" id="year" name="year"></select>
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
                                    @if (Auth::user()->isAdmin == 0 || Auth::user()->isAdmin == 1)
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

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Applicant ID</span></label>
                                <input type="text" class="form-control form-control-sm" name="admission_id" placeholder="Applicant ID">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Strand</span></label>
                                <select class="form-control  form-control-sm" name="strand">
                                    <option value="">Strand</option>
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
            <h5>Search Results:
                <small>
                    <i>Year-<b>{{ request('year') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                        ID-<b>{{ request('admission_id') }}</b>,
                        Strand-<b>{{ request('strand') }}</b>,
                    </i>
                </small>
            </h5>
        </div>
        <div class="mt-5">
            <div class="">
                <table id="examlistTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact No.</th>
                            <th>Exam Sched</th>
                            <th>Campus</th>
                            <th id="actionColumnHeader" style="display: none;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($data as $applicant)
                            @if ($applicant->p_status == 2)
                            <tr id="tr-{{ $applicant->id }}">
                                <td>{{ $no++ }}</td>
                                <td>{{ $applicant->admission_id }}</td>
                                <td style="text-transform: uppercase;">
                                    <b>{{$applicant->fname}} 
                                        @if($applicant->mname == null)
                                            @else {{ substr($applicant->mname,0,1) }}.
                                        @endif {{$applicant->lname}}  

                                        @if($applicant->ext == 'N/A') 
                                            @else{{$applicant->ext}}
                                        @endif
                                    </b>
                                </td>
                                <td>
                                    @if ($applicant->type == 1) New 
                                        @elseif($applicant->type == 2) Returnee 
                                        @elseif($applicant->type == 3) Transferee 
                                    @endif
                                </td>
                                <td>{{ $applicant->contact }}</td>
                                <td>{{ \Carbon\Carbon::parse($applicant->d_admission. ' ' . $applicant->time)->format('M. d, Y g:i A') }}</td>
                                <td>{{ $applicant->campus }}</td>
                                @if (Auth::user()->campus == request('campus'))
                                <td style="text-align:center;">
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{  route('examinee_edit', encrypt($applicant->id ))}}" class="dropdown-item btn-edit">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button value="{{ $applicant->id }}" class="dropdown-item examinee-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
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


<div class="modal fade" id="assignresultexamModal" role="dialog" aria-labelledby="assignresultexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignresultexamModalLabel">Assign Result to Examinee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="admissionAssignResult">
                <div class="modal-body">
                    <input type="hidden" name="id" id="assignresultexamId">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label><span class="badge badge-secondary">Raw Score</span></label>
                            <input type="number" class="form-control form-control-sm" name="raw_score" id="assignresultexamRawScore" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label><span class="badge badge-secondary">Remarks</span></label>
                            <input type="text" name="percentile" class="form-control form-control-sm" id="assignresultexamPercent" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pushtoresultModal" role="dialog" aria-labelledby="pushtoresultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pushtoresultModalLabel">Are you sure you want to Push the Applicant to Examination Result List?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pushtoresultForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="pushtoresultId">
                    <div class="form-group">
                        <center><button type="submit" class="btn btn-primary"><i class="fas fa-check"></i>  Yes!, Push to Examinee</button></center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var allExamlicantRoute = "{{ route('getsrchexamineeList') }}";
    var allExamAssignResultRoute = "{{ route('examinee_resultmod_save', ['id' => ':id']) }}";
    var allExamDeleteRoute = "{{ route('applicant_delete', ['id' => ':id']) }}";
    var pushtoresultRoute = '{{ route('examinee_confirmajax',  ['id' => ':id']) }}';
    var appidEncryptRoute = "{{ route('idcrypt') }}";

    var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    var requestedCampus = '{{ request('campus') }}'
</script>

@endsection

@section('script')