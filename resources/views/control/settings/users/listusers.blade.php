@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || User's List
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

                    <button type="button" class="btn btn-info btn-sm mb-4" data-toggle="modal" data-target="#buttonFilterModal">
                      <i class="fas fa-filter"></i> Filter Menu
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
                                        @if ($user->role == '0')
                                            <span class="badge badge-secondary">Administrator</span>
                                        @elseif ($user->role == '1')
                                            <span class="badge badge-primary">Guidance Officer</span>
                                        @elseif ($user->role == '2')
                                            <span class="badge badge-success">Guidance Staff</span>
                                        @elseif ($user->role == '3')
                                            <span class="badge badge-danger">Registrar</span>
                                        @elseif ($user->role == '4')
                                            <span class="badge badge-warning">Registrar Staff</span>
                                        @elseif ($user->role == '5')
                                            <span class="badge badge-info">College Dean</span>
                                        @elseif ($user->role == '6')
                                            <span class="badge badge-info">Program Head</span>
                                        @elseif ($user->role == '7')
                                            <span class="badge badge-info">College Staff</span>
                                        @elseif ($user->role == '8')
                                            <span class="badge badge-warning">Scholarship Head</span>
                                        @elseif ($user->role == '9')
                                            <span class="badge badge-warning">Scholarship Staff</span>
                                        @elseif ($user->role == '10')
                                            <span class="badge badge-warning">Assessment Head</span>
                                        @elseif ($user->role == '11')
                                            <span class="badge badge-warning">Assessment Staff</span>
                                        @elseif ($user->role == '12')
                                            <span class="badge badge-warning">MIS Staff</span>
                                        @else
                                            <span class="badge badge-light">Unknown Role</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->campus }}</td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('edit_user', ['id' => encrypt($user->id)]) }}" type="button" class="btn btn-primary btn-sm">
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
                                <!-- Button Filter Modal -->
                                <div class="modal fade" id="buttonFilterModal{{ $user->id }}" tabindex="-1" aria-labelledby="buttonFilterModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('userbuttonUpdate', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="buttonFilterModalLabel">Filter User Buttons</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="user">Name</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ $user->fname }} {{ substr($user->mname,0,1) }}. {{ $user->lname }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="user">Role</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ 
                                                            $user->role == 0 ? 'Administrator' :
                                                            ($user->role == 1 ? 'Guidance Officer' :
                                                            ($user->role == 2 ? 'Guidance Staff' :
                                                            ($user->role == 3 ? 'Registrar' :
                                                            ($user->role == 4 ? 'Registrar Staff' :
                                                            ($user->role == 5 ? 'College Dean' :
                                                            ($user->role == 6 ? 'Program Head' :
                                                            ($user->role == 7 ? 'College Staff' :
                                                            ($user->role == 8 ? 'Scholarship Head' :
                                                            ($user->role == 9 ? 'Scholarship Staff' :
                                                            ($user->role == 10 ? 'Assessment Head' :
                                                            ($user->role == 11 ? 'Assessment Staff' : 'Unknown Role'))))))))))) 
                                                        }}" readonly>
                                                    </div>
                                                    <div class="form-group" id="buttonSelection">
                                                        <label for="buttons">Select Buttons</label>
                                                        @php
                                                            $buttons = [
                                                                'admission-url' => 'Admission',
                                                                'enrollment-url' => 'Enrollment',
                                                                'scheduler-url' => 'Scheduling',
                                                                'assessment-url' => 'Assessment',
                                                                'cashiering-url' => 'Cashiering',
                                                                'scholarship-url' => 'Scholarship',
                                                                'grading-url' => 'Grading',
                                                                'request-url' => 'Request',
                                                                'setting-url' => 'Settings',
                                                            ];
                                                            $userButtons = json_decode($user->buttons, true) ?? [];
                                                        @endphp

                                                        @foreach($buttons as $value => $label)
                                                            <div class="icheck-success">
                                                                <input type="checkbox" id="{{ $value }}-{{ $user->id }}" name="buttons[]" value="{{ $value }}"
                                                                @if(in_array($value, $userButtons)) checked @endif>
                                                                <label for="{{ $value }}-{{ $user->id }}">{{ $label }}</label>
                                                            </div>
                                                        @endforeach
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="editUserAccntModal" tabindex="-1" role="dialog" aria-labelledby="editUserAccntModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserAccntModalLabel">Edit Fund Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserAccntForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUserAccntId">
                    <div class="form-group">
                        <label for="editFundName">Fund Name</label>
                        <input type="text" class="form-control" id="editFundName" name="fund_name">
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
    var useraccountRoute = "{{ route('getusersRead') }}";
    var setconfCreateRoute = "{{ route('setconfCreate') }}";
    var setconfUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
</script>

@endsection

@section('script')