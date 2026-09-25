@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style="background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Enrollment History</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Enrollment History</h1>
                        <p class="text-muted small mb-0">View student enrollment history.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('viewsearchenStudHistory') }}" id="studscholar">
                                    @csrf

                                    <div class="mt-2">
                                        <div class="form-group">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Enter Student Last name or Student ID No.: <span class="text-danger">*</span></label>
                                                    <input type="text" name="query" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" placeholder="Search Student Lastname or Student ID No." autofocus required>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="d-flex flex-column h-100">
                                                        <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                        <button type="submit" class="btn btn-success btn-sm">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function formatInput(input) {
            let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');

            if (cleaned.length > 0) {
                let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
                input.value = formatted;
            } else {
                input.value = '';
            }
        }

        function handleDelete(event) {
            if (event.key === 'Backspace') {
                let input = event.target;
                let value = input.value;
                input.value = value.substring(0, value.length - 1);
                formatInput(input);
            }
        }

        var selectQueueCatRoute  = "{{ route('counterUserUpdate') }}";
        var studqueuelistReadRoute = "{{ route('studqueuefetch') }}";
        var studqueuelistShowReadRoute = "{{ route('editsearchStudRead') }}";
    </script>
@endsection
