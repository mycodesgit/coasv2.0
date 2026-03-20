@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Rooms</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Rooms</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editRoomModal" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel"><i class="fas fa-pen"></i> Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRoomForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editRoomId">
                        <div class="form-group">
                            <label>College: <span class="text-danger">*</span></label>
                            <select id="college_room" class="form-control form-control-sm" id="editRoomCollege" name="college_room">
                                <option disabled selected> ---Select---</option>
                                @foreach($collegelist as $datacollegelist)
                                    <option value="{{ $datacollegelist->id }}">{{ $datacollegelist->college_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editRoomName">Room: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editRoomName" name="room_name">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editRoomCapacity">Capacity: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="editRoomCapacity" name="room_capacity">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
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
