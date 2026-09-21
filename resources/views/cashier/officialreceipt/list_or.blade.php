@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || Cashiering
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
                            <li class="breadcrumb-item mt-1">Cashier</li>
                            <li class="breadcrumb-item active mt-1">Official Receipt</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Official Receipt</h1>
                        <p class="text-muted small mb-0">Student payment, daily transactions logs.</p>
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <form method="GET" action="{{ route('listsearch_orRead') }}"  id="adOR">
                                            @csrf
                                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                <h5>Add</h5>
                                            </div>

                                            <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">O.R. Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="orno" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" autofocus>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>---Select---</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Student ID Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()">
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-success d-inline">
                                                                <input type="radio" name="r3" value="on" checked="checked" id="radioSuccess1">
                                                                <label for="radioSuccess1">
                                                                    With Student ID
                                                                </label>
                                                            </div>
                                                            &nbsp;&nbsp;
                                                            <div class="icheck-success d-inline">
                                                                <input type="radio" name="r3" value="off" id="radioSuccess2">
                                                                <label for="radioSuccess2">
                                                                    Without Student ID
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-9">
                                        <table id="ortable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Fund</th>
                                                    <th>Account Name</th>
                                                    <th>Amount</th>
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
    </script>
@endsection
