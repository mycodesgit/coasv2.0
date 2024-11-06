@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Applicant Change Campus
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
            <li class="breadcrumb-item active mt-1">Applicant Change Campus</li>
        </ol>

        <div class="page-header">
            <form method="GET" action="{{ route('alllistappRead_search') }}">
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
                                            @elseif(Auth::user()->campus == 'VE') Valladolid 
                                        @endif
                                    </option>
                                    @if (Auth::user()->role == 0 || Auth::user()->role == 1)
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
                                        <option value="VE">Valladolid</option>
                                    @else
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Strand</span></label>
                                <select class="form-control  form-control-sm" name="strand">
                                    <option value=""> --Select-- </option>
                                    @foreach($strand as $datastrand)
                                        <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                    @endforeach
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
                        Strand-@if(request('strand'))<b>{{ request('strand') }}</b>@else <b>All Strand</b> @endif
                    </i>
                </small>
            </h5>
        </div>

        <div class="page-header mt-2" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-5">
            <div class="">
                <table id="applistChangeTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact No.</th>
                            <th>Date Applied</th>
                            <th>Campus</th>
                            <th>Strand</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($data as $applicant)
                            @if ($applicant->p_status == 1)
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
                                <td>{{ $applicant->created_at->format('M. d, Y') }}</td>
                                <td>{{ $applicant->campus }}</td>
                                @if (Auth::user()->campus == request('campus'))
                                <td style="text-align:center;">
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{  route('applicant_edit', encrypt($applicant->id ))}}" class="dropdown-item btn-edit">
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


<div class="modal fade" id="pushtoexamModal" role="dialog" aria-labelledby="pushtoexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pushtoexamModalLabel">Are you sure you want to Push the Applicant to Examinee List?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pushtoexamForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="pushtoexamId">
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


<div class="modal fade" id="changecampModal" role="dialog" aria-labelledby="changecampModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changecampModalLabel">Change Campus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changecampForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="changecampId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="changecampName"><span class="badge badge-secondary">Applicant Name</span></label>
                                <input type="text" class="form-control form-control-sm" id="changecampName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="changecampAdID"><span class="badge badge-secondary">Admission ID</span></label>
                                <input type="text" class="form-control form-control-sm" id="changecampAdID" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="changecampStrand"><span class="badge badge-secondary">Strand</span></label>
                                <input type="text" class="form-control form-control-sm" id="changecampStrand" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="changecampCamp"><span class="badge badge-secondary">Campus</span></label>
                                <input type="text" class="form-control form-control-sm" id="changecampCamp" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="changecampStrand"><span class="badge badge-warning">Change to his/her preferred Campus</span></label>
                                <select class="form-control form-control-sm" name="campus" id="campus">
                                    <option disabled selected> --Select-- </option>
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
                                </select>
                            </div>
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

<script>
    var allApplicantchngecampRoute = "{{ route('getalllistappRead_search') }}";
    var updateallApplicantchngecampRoute = "{{ route('alllistappUpdate', ['id' => ':id']) }}";
    var appidEncryptRoute = "{{ route('idcrypt') }}";
</script>

@endsection

@section('script')