@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Queueing
@endsection


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
                            <li class="breadcrumb-item mt-1">Queueing</li>
                            <li class="breadcrumb-item active mt-1">Numbers</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Numbers</h1>
                        <p class="text-muted small mb-0">Manage numbers to generate in all windows for queueing.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add Numbers
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{route('storeQueueNumbers')}}" id="numberad">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Start: <span class="text-danger">*</span></label>
                                                <input type="number" name="start" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">End: <span class="text-danger">*</span></label>
                                                <input type="number" name="end" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Category: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="catname">
                                                    <option value="Enrollment">Enrollment</option>
                                                    <option value="Processing">Processing</option>
                                                    <option value="Pre-register">Pre-register</option>
                                                    <option value="Printing">Printing</option>
                                                    <option value="Evaluation">Evaluation</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Counter: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="available_in[]" multiple="">
                                                    @foreach($counterwin as $datacounterwin)
                                                        <option>{{ $datacounterwin->windowname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-between">
                                                <button type="reset" class="btn btn-light"><i class="ti ti-restore"></i> Clear</button>
                                                <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-numbers"></i> List of Numbers
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3 p-2">
                                    <table id="numberTable" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Queue Numbers</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Campus</th>
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

    <script>
        var numberRoute = "{{ route('getnumberRead') }}";
        var numberCreateRoute = "{{ route('storeQueueNumbers') }}";
        var counterUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
    </script>
@endsection
