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
                            <form method="post" action="{{route('storeQueueNumbers')}}" id="numberad">
                                @csrf
                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                    <h5>Add Queue Number</h5>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Start</span></label>
                                            <input type="number" name="start" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                        </div>

                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">End</span></label>
                                            <input type="number" name="end" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                        </div>

                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Category</span></label>
                                            <select class="form-control form-control-sm" name="catname">
                                                <option value="Enrollment">Enrollment</option>
                                                <option value="Processing">Processing</option>
                                            </select>
                                        </div>

                                        <div class="mt-2 col-md-12">
                                            <label><span class="badge badge-secondary">Counter</span></label>
                                            <select class="form-control form-control-sm select2bs4" name="available_in[]" multiple="">
                                                @foreach($counterwin as $datacounterwin)
                                                    <option>{{ $datacounterwin->windowname }}</option>
                                                @endforeach
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
                    <table id="numberTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Queue Numbers</th>
                                <th>Category</th>
                                <th>Status</th>
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

<script>
    var numberRoute = "{{ route('getnumberRead') }}";
    var numberCreateRoute = "{{ route('storeQueueNumbers') }}";
    var counterUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
</script>

@endsection

@section('script')