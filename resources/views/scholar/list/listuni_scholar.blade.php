@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || CPSU Scholarship
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
            <li class="breadcrumb-item active mt-1">CPSU Scholarship</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal-unisch">
                <i class="fas fa-plus"></i> Add New
            </button>
            @include('modal.unischAdd')
            <div class="">
                <table id="unischtable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Scholarship Category</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($schuni as $schuniv)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $schuniv->unisch_name }}</td>
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


<div class="modal fade" id="editUNISchModal" role="dialog" aria-labelledby="editUNISchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUNISchModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUNISchForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUNISchId">
                    <div class="form-group">
                        <label for="editUNISchName">Scholarship</label>
                        <textarea class="form-control form-control-sm" name="unisch_name" id="editUNISchName" rows="3"></textarea>
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
    var unischcatReadRoute = "{{ route('getunischolarlist') }}";
    var unischcatCreateRoute = "{{ route('unischolarCreate') }}";
    var unischcatUpdateRoute = "{{ route('unischolarUpdate', ['id' => ':id']) }}";
    var unischcatDeleteRoute = "{{ route('unischolarDelete', ['id' => ':id']) }}";
    var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
</script>


@endsection

@section('script')
