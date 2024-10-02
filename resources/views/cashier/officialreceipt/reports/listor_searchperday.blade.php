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
            <li class="breadcrumb-item active mt-1">Official Receipt Per Date</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Official Receipt Per Date</h4>
        </div>

        <div class="mt-3 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('listsearch_orperdayRead') }}" id="perdayorno">
                    @csrf

                    <div class="">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label><span class="badge badge-secondary">Select Date</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="date" name="datepaid" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-12 mt-2">
                <div class="page-header" style="border-top: 1px solid #04401f;"></div>
                <div class="mt-3">
                    <table id="example1" class="table table-hover">
                        <thead>
                            <tr>
                                <th>OR No</th>
                                <th>Date Paid</th>
                                <th>Schlyear</th>
                                <th>Semester</th>
                                <th>Student ID No.</th>
                                <th>Student Name</th>
                                <th>Amount</th>
                                <th>PostedBy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{ $d->orno }}</td>
                                    <td>{{ $d->datepaid }}</td>
                                    <td>{{ $d->schlyear }}</td>
                                    <td>
                                        @if($d->semester == 1)
                                            1st Semester
                                        @elseif($d->semester == 2)
                                            2nd Semester
                                        @elseif($d->semester == 3)
                                            Summer
                                        @else
                                            Unknown Semester
                                        @endif
                                    </td>
                                    <td>{{ $d->studID }}</td>
                                    <td>{{ $d->slname }}, {{ $d->sfname }} {{ substr($d->smname, 0,1) }}.</td>
                                    <td>{{ number_format($d->total_amount, 2) }}</td>
                                    <td>{{ $d->fname }} {{ $d->lname }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
