@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || CHED Scholarship
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
            <li class="breadcrumb-item active mt-1">CHED Scholarship</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#modal-chedsch">
                <i class="fas fa-plus"></i> Add New
            </button>
            @include('modal.chedschAdd')
            <div class="">
                <table id="chedschtable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Scholarship Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($schched as $schoched)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $schoched->chedsch_name }}</td>
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

<div class="modal fade" id="editCHEDSchModal" role="dialog" aria-labelledby="editCHEDSchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCHEDSchModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCHEDSchForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCHEDSchId">
                    <div class="form-group">
                        <label for="editCHEDSchName">Scholarship</label>
                        <textarea class="form-control form-control-sm" name="chedsch_name" id="editCHEDSchName" rows="3"></textarea>
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
    var chedschcatReadRoute = "{{ route('getchedscholarlist') }}";
    var chedschcatCreateRoute = "{{ route('chedscholarCreate') }}";
    var chedschcatUpdateRoute = "{{ route('chedscholarUpdate', ['id' => ':id']) }}";
    var chedschcatDeleteRoute = "{{ route('chedscholarDelete', ['id' => ':id']) }}";
    var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
</script>

@endsection

@section('script')
