@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student Evaluation
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Student Evaluation</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>Student Evaluation</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-9">
                    <form method="GET" action="{{ route('loadstudsub_searchview') }}" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Student ID Number</span></label>
                                    <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                        {{-- <option value="2025-2026">2025-2026</option> --}}
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control form-control-sm" name="semester">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->semester }}">
                                                @if($datasy->semester == 1)
                                                    First Semester
                                                @elseif($datasy->semester == 2)
                                                    Second Semester
                                                @elseif($datasy->semester == 3)
                                                    Summer
                                                @else
                                                    Unknown Semester
                                                @endif
                                            </option>
                                        @endforeach
                                        {{-- <option value="1">1st Semester</option> --}}
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if(in_array(Auth::guard('web')->user()->campus, ['MC']))
                        @if($queueMode->statusqueue === 'Off')

                        @else
                            <table id="holdTable" class="table table-hover">
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
                        @endif
                    @endif
                </div>
                @if($queueMode->statusqueue === 'Off')
                @else
                    <div class="col-md-3">
                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="card" style="background-color: #dfdfdf">
                                        <div class="card-body">
                                            <center><label>Current No.</label></center>
                                            <input type="text" id="queueNumber" class="form-control text-bold" readonly style="border: none; font-size: 20pt; text-align: center;">
                                            <button id="nextButton" class="btn btn-primary btn-block mt-3" data-counter-id="1">Next</button> 
                                            <button id="callButton" class="btn btn-danger btn-block mt-2">Call</button>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
