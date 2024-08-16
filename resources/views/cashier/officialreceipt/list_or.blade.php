@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || OR
@endsection

@section('sideheader')
<h4>Cashier</h4>
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
            <li class="breadcrumb-item mt-1">Cashier</li>
            <li class="breadcrumb-item active mt-1">Official Receipt</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Official Receipt</h4>
        </div>

        <div class="mt-3 row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ route('listsearch_orRead') }}" id="adOR">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Official Receipt</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">O.R. Number</span></label>
                                        <input type="text" name="orno" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" autofocus>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Semester</span></label>
                                        <select class="form-control form-control-sm" name="semester">
                                            <option disabled selected>---Select---</option>
                                            <option value="1">First Semester</option>
                                            <option value="2">Second Semester</option>
                                            <option value="3">Summer</option>
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">School Year</span></label>
                                        <select class="form-control form-control-sm" name="schlyear">
                                            @foreach($sy as $datasy)
                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Student ID Number</span></label>
                                        <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()">
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">OK</button>
                                    </div>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
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

<div class="modal fade" id="editFundModal" tabindex="-1" role="dialog" aria-labelledby="editFundModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit Fund Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFundForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editFundId">
                    <div class="form-group">
                        <label for="editFundName">Fund Name</label>
                        <input type="text" class="form-control" id="editFundName" name="fund_name">
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
