@extends('layouts.master_settings')

@section('title')
COAS - V2.0 || User's List
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
                                        <button class="btn btn-primary btn-sm btn-edit"
                                            data-toggle="modal"
                                            data-target="#buttonFilterModal{{ $user->id }}"
                                            data-event-id="{{ $user->id }}"
                                            data-buttonmenus="{{ json_encode($user->buttons) }}">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </button>
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

                                <div class="modal fade" id="buttonFilterModal{{ $user->id }}" tabindex="-1" aria-labelledby="buttonFilterModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('filterButtons') }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="buttonFilterModalLabel">Filter User Buttons</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="user">User</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ $user->fname }} {{ $user->lname }}" readonly>
                                                    </div>
                                                    <div class="form-group" id="buttonSelection">
                                                        <label for="buttons">Select Buttons</label>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="admission" name="buttons[]" value="admission-url">
                                                            <label for="admission">Admission</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="enrollment" name="buttons[]" value="enrollment-url">
                                                            <label for="enrollment">Enrollment</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="schedule" name="buttons[]" value="scheduler-url">
                                                            <label for="schedule">Scheduling</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="assesment" name="buttons[]" value="assessment-url">
                                                            <label for="assesment">Assessment</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="cashiering" name="buttons[]" value="cashiering-url">
                                                            <label for="cashiering">Cashiering</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="scholarship" name="buttons[]" value="scholarship-url">
                                                            <label for="scholarship">Scholarship</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="grading" name="buttons[]" value="grading-url">
                                                            <label for="grading">Grading</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="request" name="buttons[]" value="request-url">
                                                            <label for="request">Request</label>
                                                        </div>

                                                        <div class="icheck-success">
                                                            <input type="checkbox" id="setting" name="buttons[]" value="setting-url">
                                                            <label for="setting">Settings</label>
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
@endsection

@section('script')