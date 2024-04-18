@extends('layouts.master_settings')

@section('title')
COAS - V1.0 || User's List
@endsection

@section('sideheader')
<h4>Settings</h4>
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
            <li class="breadcrumb-item mt-1">Settings</li>
            <li class="breadcrumb-item active mt-1">User's List</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#modal-user">
                        <i class="fas fa-user-plus"></i> Add New
                    </button>

                    @include('modal.userAdd')

                    <table id="example1" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Campus</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($data as $user)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="text-transform: uppercase;">
                                        <b>{{$user->fname}} 
                                            @if($user->mname == null)
                                                @else {{ substr($user->mname,0,1) }}.
                                            @endif {{$user->lname}}  

                                            @if($user->ext == 'N/A') 
                                                @else{{$user->ext}}
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->isAdmin == 0)
                                            <span class="badge badge-secondary">Administrator</span>
                                        @elseif ($user->isAdmin == 1)
                                            <span class="badge badge-primary">Guidance Officer</span>
                                        @elseif ($user->isAdmin == 2)
                                            <span class="badge badge-success">Guidance Staff</span>
                                        @elseif ($user->isAdmin == 3)
                                            <span class="badge badge-danger">Registrar</span>
                                        @elseif ($user->isAdmin == 4)
                                            <span class="badge badge-warning">Registrar Staff</span>
                                        @elseif ($user->isAdmin == 5)
                                            <span class="badge badge-info">College Dean</span>
                                        @elseif ($user->isAdmin == 6)
                                            <span class="badge badge-info">Program Head</span>
                                        @elseif ($user->isAdmin == 7)
                                            <span class="badge badge-info">College Staff</span>
                                        @elseif ($user->isAdmin == 8)
                                            <span class="badge badge-warning">Scholarship Head</span>
                                        @elseif ($user->isAdmin == 9)
                                            <span class="badge badge-warning">Scholarship Staff</span>
                                        @else
                                            <span class="badge badge-light">Unknown Role</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->campus }}</td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('edit_user', ['id' => encrypt($user->id)]) }}" type="button" class="btn btn-primary">
                                            <i class="fas fa-cog"></i>
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
</div>


@endsection

@section('script')