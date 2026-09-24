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
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
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
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Rooms</h1>
                        <p class="text-muted small mb-0">Manage Rooms</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Rooms
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive p-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-room">
                                            <i class="fas fa-user-plus"></i> Add New
                                        </button>

                                        <table id="classRooms" class="table table-hover" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>College</th>
                                                    <th>Room Name</th>
                                                    <th>Room Capacity</th>
                                                    <th>Campus</th>
                                                    <th>Status</th>
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

    <div class="modal fade" id="modal-room" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="modal-roomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-roomLabel">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form class="form-horizontal" action="{{ route('roomsCreate') }}" method="post" id="addRoom">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Belongs to: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="college_room">
                                        <option disabled selected> ---Select---</option>
                                        @foreach($collegelist as $datacollegelist)
                                            <option value="{{ $datacollegelist->id }}">{{ $datacollegelist->college_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Room name: <span class="text-danger">*</span></label>
                                    <input type="text" name="room_name" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Room Capacity: <span class="text-danger">*</span></label>
                                    <input type="number" name="room_capacity" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editRoomModal" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel"><i class="ti ti-pencil"></i> Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRoomForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editRoomId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">College: <span class="text-danger">*</span></label>
                                <select id="college_room" class="form-control form-control-sm" id="editRoomCollege" name="college_room">
                                    <option disabled selected> ---Select---</option>
                                    @foreach($collegelist as $datacollegelist)
                                        <option value="{{ $datacollegelist->id }}">{{ $datacollegelist->college_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editRoomName">Room: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editRoomName" name="room_name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editRoomCapacity">Capacity: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editRoomCapacity" name="room_capacity">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editRoomStatus">Status: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="editRoomStatus" name="status">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i> Save changes</button>
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
