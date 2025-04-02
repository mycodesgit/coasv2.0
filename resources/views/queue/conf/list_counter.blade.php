@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Counter List
@endsection

@section('sideheader')
<h4>Queueing</h4>
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
            <li class="breadcrumb-item mt-1">Queueing</li>
            <li class="breadcrumb-item active mt-1">Counter's List</li>
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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{route('counterCreate')}}" id="counterad">
                                @csrf
                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                    <h5>Add Counter Name</h5>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Counter</span></label>
                                            <input type="number" name="windowname" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                        </div>

                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Counter</span></label>
                                            <select class="form-control form-control-sm" name="useridlog">
                                                <option disabled selected> --Select-- </option>
                                                @foreach($user as $datauser)
                                                    <option value="{{ $datauser->id }}">{{ $datauser->fname }} {{ $datauser->lname }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Category</span></label>
                                            <select class="form-control form-control-sm" name="category">
                                                <option value="Enrollment">Enrollment</option>
                                                <option value="Processing">Processing</option>
                                                <option value="Pre-register">Pre-register</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table id="counterTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Counter Name</th>
                                <th>Category</th>
                                <th>Assign</th>
                                <th>Campus</th>
                                <th width="10%">Action</th>
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

<div class="modal fade" id="editCounterModal" tabindex="-1" role="dialog" aria-labelledby="editCounterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCounterModalLabel">Edit Counter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCounterForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCounterId">
                    <div class="form-group">
                        <label for="editCounterName">Name</label>
                        <select class="form-control form-control-sm" name="useridlog" id="editCounterName">
                            <option disabled selected> --Select-- </option>
                            @foreach($user as $datauser)
                                <option value="{{ $datauser->id }}">{{ $datauser->fname }} {{ $datauser->lname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editCounterCategoty">Category</label>
                        <select name="category" class="form-control form-control-sm" id="editCounterCategoty">
                            <option value="Enrollment">Enrollment</option>
                            <option value="Processing">Processing</option>
                            <option value="Pre-register">Pre-register</option>
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
    var counterRoute = "{{ route('getcounterRead') }}";
    var counterCreateRoute = "{{ route('counterCreate') }}";
    var counterUpdateRoute = "{{ route('counterUpdate', ['id' => ':id']) }}";
</script>

@endsection

@section('script')