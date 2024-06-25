@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || All Scholarship
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">All Scholarship</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal-allsch">
                <i class="fas fa-plus"></i> Add New
            </button>
            @include('modal.allschAdd')
            <div class="">
                <table id="allschTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Scholarship Name</th>
                            <th width="20%">Sponsor</th>
                            <th>CHED Cat</th>
                            <th>CPSU Cat</th>
                            <th>FS</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($data as $scholars)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $scholars->scholar_name }}</td>
                                <td>{{ $scholars->scholar_sponsor }}</td>
                                <td>{{ $scholars->chedsch_name }}</td>
                                <td>{{ $scholars->unisch_name }}</td>
                                <td>{{ $scholars->fndsource_name }}</td>
                                <td style="text-align:center;">
                                    <a href="" type="button" class="btn btn-primary btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAllSchModal" role="dialog" aria-labelledby="editAllSchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAllSchModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAllSchForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editSchChoiceId">
                    <div class="form-group">
                        <label for="editSchChoiceName">Scholarship</label>
                        <input type="text" class="form-control form-control-sm" name="scholar_name" id="editSchChoiceName">
                    </div>
                    <div class="form-group">
                        <label for="editSchSponChoiceName">Scholarship</label>
                        <textarea class="form-control form-control-sm" name="scholar_sponsor" id="editSchSponChoiceName" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editSchchedChoiceName">CHED Scholarship Category</label>
                        <select class="form-control form-control-sm" name="chedcategory" id="edichedChoiceName">
                            <option disabled selected>--Select--</option>
                            @foreach($ched as $datached)
                                <option value="{{ $datached->id }}">{{ $datached->chedsch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSchuniChoiceName">CPSU Scholarship Category</label>
                        <select class="form-control form-control-sm" name="unicategory" id="ediuniChoiceName">
                            <option disabled selected>--Select--</option>
                            @foreach($uni as $datauni)
                                <option value="{{ $datauni->id }}">{{ $datauni->unisch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSchfsChoiceName">Funding Source</label>
                        <select class="form-control form-control-sm" name="fund_source" id="edifsChoiceName">
                            <option disabled selected>--Select--</option>
                            @foreach($fs as $datafs)
                                <option value="{{ $datafs->id }}">{{ $datafs->fndsource_name }}</option>
                            @endforeach
                        </select>
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
    var allschcatReadRoute = "{{ route('getallscholarlist') }}";
    var allschcatCreateRoute = "{{ route('allscholarCreate') }}";
    var allschcatUpdateRoute = "{{ route('allscholarUpdate', ['id' => ':id']) }}";
    var idSchEncryptRoute = "{{ route('idcrypt') }}";
    var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
</script>

@endsection

@section('script')
