@extends('layouts.master_settings')

@section('title')
COAS - V2.0 || Configure
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
            <li class="breadcrumb-item active mt-1">Configure</li>
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
                    <button type="button" class="btn btn-secondary btn-sm mb-4" data-toggle="modal" data-target="#modal-setconf">
                        <i class="fas fa-cog"></i> Configure New
                    </button>

                    @include('modal.settingsconf')

                    <div class="tale-responsive">
                        <table id="setconftable" class="table table-hover">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <th>School Year</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php $no = 1; @endphp
                                @foreach($sttngs as $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data->schlyear }}</td>
                                        <td>{{ $data->semester }}</td>
                                        <td>
                                            @if($data->set_status == 1)
                                                <span class="badge badge-danger">Inactive</span>
                                            @elseif($data->set_status == 2)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                {{ $data->set_status }}
                                            @endif
                                        </td>
                                        <td>{{ $data->updated_at->format('F d, Y h:i A') }}</td>
                                        <td style="text-align:center;">
                                            <a href="" type="button" class="btn btn-primary">
                                                <i class="fas fa-cog"></i>
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
    </div>
</div>

<div class="modal fade" id="editSetConfModal" tabindex="-1" role="dialog" aria-labelledby="editSetConfModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSetConfModalLabel">Edit School Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSetConfForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editSetConfId">

                    <div class="form-group">
                        <label for="editSetConfschlyear">School Year</label>
                        <input type="text" class="form-control form-control-sm" name="schlyear" id="editSetConfschlyear">
                    </div>

                    <div class="form-group">
                        <label for="editCollegeName">Semester</label>
                        <select class="form-control form-control-sm" name="semester" id="editSetConfsemester">
                            <option value="1">First Semester</option>
                            <option value="2">Second Semester</option>
                            <option value="3">Summer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="editCollegeName">Status</label>
                        <select class="form-control form-control-sm" name="set_status" id="editSetConfstatus">
                            <option value="1">Deactivate</option>
                            <option value="2">Activate</option>
                        </select>
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
    var setconfRoute = "{{ route('getsetconfigure') }}";
    var setconfCreateRoute = "{{ route('setconfCreate') }}";
    var setconfUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
</script>

@endsection

@section('script')