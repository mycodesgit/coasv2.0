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

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h5>Search Results: {{-- {{ $totalSearchResults }}  --}}
                <small>
                    <i>
                        Category-<b>{{ request('category') }}</b>,
                    </i>
                </small>
            </h5>
        </div>
        <div class="mt-5">
            <div class="">
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Scholarship Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
