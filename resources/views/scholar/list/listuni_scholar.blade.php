@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || CPSU Scholarship
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

<script>
    var unischcatReadRoute = "{{ route('getunischolarlist') }}";
    var chedschcatCreateRoute = "{{ route('chedscholarCreate') }}";
    var chedschcatUpdateRoute = "{{ route('chedscholarUpdate', ['id' => ':id']) }}";
    var chedschcatDeleteRoute = "{{ route('chedscholarDelete', ['id' => ':id']) }}";
    var isAdmin = '{{ Auth::user()->isAdmin == "0" }}';
</script>


@endsection

@section('script')
