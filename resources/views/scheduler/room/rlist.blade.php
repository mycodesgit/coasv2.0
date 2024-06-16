@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Rooms
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">Rooms</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Rooms</h4>
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('roomsCreate') }}" id="adRoom">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add Room</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Belongs to</span></label>
                                        <select class="form-control form-control-sm" name="college_room">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($collegelist as $datacollegelist)
                                                <option value="{{ $datacollegelist->id }}">{{ $datacollegelist->college_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Room name</span></label>
                                        <input type="text" name="room_name" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Room Capacity</span></label>
                                        <input type="number" name="room_capacity" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table id="classRooms" class="table table-hover">
                    <thead>
                        <tr>
                            <th>College</th>
                            <th>Room Name</th>
                            <th>Room Capacity</th>
                            <th>Campus</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRoomModal" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRoomForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editRoomId">
                    <div class="form-group">
                        <label for="editRoomCollege">College</label>
                        <select id="college_room" class="form-control form-control-sm" id="editRoomCollege" name="college_room">
                            <option disabled selected> ---Select---</option>
                            @foreach($collegelist as $datacollegelist)
                                <option value="{{ $datacollegelist->id }}">{{ $datacollegelist->college_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editRoomName">Room</label>
                        <input type="text" class="form-control" id="editRoomName" name="room_name">
                    </div>
                    <div class="form-group">
                        <label for="editRoomCapacity">Capacity</label>
                        <input type="number" class="form-control" id="editRoomCapacity" name="room_capacity">
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
    var roomsReadRoute = "{{ route('getroomsRead') }}";
    var roomsCreateRoute = "{{ route('roomsCreate') }}";
    var roomsUpdateRoute = "{{ route('roomsUpdate', ['id' => ':rmid']) }}";
    var roomsDeleteRoute = "{{ route('roomsDelete', ['id' => ':rmid']) }}";
    var roomidEncryptRoute = "{{ route('idcrypt') }}";
</script>

@endsection
