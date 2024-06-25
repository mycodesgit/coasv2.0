@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Accepted Applicants Reports
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
            <li class="breadcrumb-item active mt-1">Accepted Applicants Reports</li>
        </ol>

        <div class="page-header">
            <form method="GET" action="{{ route('accepted_reports') }}" id="">
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
                                <select class="form-control form-control-sm" name="campus" id="campus">
                                    <option value="{{Auth::user()->campus}}">
                                        @if (Auth::user()->campus == 'MC') Main 
                                            @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                            @elseif(Auth::user()->campus == 'VC') Victorias 
                                            @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                            @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                            @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                            @elseif(Auth::user()->campus == 'SC') Sipalay 
                                            @elseif(Auth::user()->campus == 'IC') Ilog 
                                            @elseif(Auth::user()->campus == 'CC') Cauayan 
                                        @endif
                                    </option>
                                    @if (Auth::user()->isAdmin == 0)
                                        <option value="MC">Main</option>
                                        <option value="SCC">San Carlos</option>
                                        <option value="VC">Victorias</option>
                                        <option value="HC">Hinigaran</option>
                                        <option value="MP">Moises Padilla</option>
                                        <option value="HinC">Hinobaan</option>
                                        <option value="SC">Sipalay</option>
                                        <option value="IC">Ilog</option>
                                        <option value="CC">Cauayan</option>
                                    @else
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-3">
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
        </div>
        <div class="page-header mt-2" style="border-bottom: 1px solid #04401f;"></div>

        <form method="GET" action="{{ route('acceptedPDF_reports') }}" id="" target="_blank">
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
            <div class="">
                <table id="report" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>App ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Course</th>
                            <th>Strand</th>
                            <th>Interviewer</th>
                            <th>Campus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach($data as $dataaccept)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $dataaccept->admission_id }}</td>
                                <td style="text-transform: uppercase;">
                                    <b>{{$dataaccept->fname}} 
                                        @if($dataaccept->mname == null)
                                            @else {{ substr($dataaccept->mname,0,1) }}.
                                        @endif {{$dataaccept->lname}}  

                                        @if($dataaccept->ext == 'N/A') 
                                            @else{{$dataaccept->ext}}
                                        @endif
                                    </b>
                                </td>
                                <td>
                                    @if ($dataaccept->type == 1) New 
                                        @elseif($dataaccept->type == 2) Returnee 
                                        @elseif($dataaccept->type == 3) Transferee 
                                    @endif
                                </td>
                                <td>{{ $dataaccept->course }}</td>
                                <td>{{ $dataaccept->strand }}</td>
                                <td>{{ $dataaccept->interviewer }}</td>
                                <td>{{ $dataaccept->campus }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')