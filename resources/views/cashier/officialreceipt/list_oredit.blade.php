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
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Cashier</li>
                            <li class="breadcrumb-item active mt-1">Edit Official Receipt</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Edit Official Receipt</h4>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="GET" action="{{ route('listsearchedit_orRead') }}"  id="adOR">
                                                @csrf

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                                <div class="form-group mt-2">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label>O.R. Number: <span class="text-danger">*</span></label>
                                                            <input type="text" name="orno" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" autofocus>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9 mt-3">
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
