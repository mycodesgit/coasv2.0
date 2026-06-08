@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Queueing
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
                            <li class="breadcrumb-item mt-1">Queueing</li>
                            <li class="breadcrumb-item active mt-1">Counters</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Counters</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <form method="post" action="{{route('counterCreate')}}" id="counterad">
                                                    @csrf
                                                    <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                        <h5>Add Counter Name</h5>
                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <div class="row g-3">
                                                            <div class="mt-2 col-md-12">
                                                                <label>Counter: <span class="text-danger">*</span></label>
                                                                <input type="number" name="windowname" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                                            </div>

                                                            <div class="mt-2 col-md-12">
                                                                <label>Counter: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="useridlog">
                                                                    <option disabled selected> --Select-- </option>
                                                                    @foreach($user as $datauser)
                                                                        <option value="{{ $datauser->id }}">{{ $datauser->fname }} {{ $datauser->lname }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mt-2 col-md-12">
                                                                <label>Category: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="category">
                                                                    <option value="Enrollment">Enrollment</option>
                                                                    <option value="Processing">Processing</option>
                                                                    <option value="Pre-register">Pre-register</option>
                                                                    <option value="Print">Printing</option>
                                                                    <option value="Evaluation">Evaluation</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9 mt-3">
                                        <div class="table-responsive mt-3 p-2">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var counterRoute = "{{ route('getcounterRead') }}";
        var counterCreateRoute = "{{ route('counterCreate') }}";
        var counterUpdateRoute = "{{ route('counterUpdate', ['id' => ':id']) }}";
    </script>
@endsection
