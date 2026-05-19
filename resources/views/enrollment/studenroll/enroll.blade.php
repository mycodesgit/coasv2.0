@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Enroll Student</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Enroll Student</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-9"> 
                                        <form method="GET" action="{{ route('searchStudEnroll') }}" id="enrollStud" class="mb-3">
                                            @csrf 

                                            <div class="form-group mt-2">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Student ID Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            {{-- @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->semester }}">{{ $datasy->semester == 1 ? '1st Semester' : ($datasy->semester == 2 ? '2nd Semester' : 'Summer') }}</option>
                                                            @endforeach --}}
                                                            <option value="3">Summer</option>
                                                            <option value="2">2nd Semester</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                        
                                        @if(in_array(Auth::guard('web')->user()->campus, ['MC', 'VC', 'SCC', 'HC', 'MP', 'IC', 'CA', 'CC', 'SC', 'HinC']))
                                            @if($queueMode->statusqueue === 'Off')

                                            @else
                                                <div class="mt-5">
                                                    <h5>List of Pre-Enrolled Students</h5>
                                                    <table id="queueTable" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Student ID No.</th>
                                                                <th>Fullname</th>
                                                                <th>Course Yr&Section</th>
                                                                <th>Campus</th>
                                                                <th>Status</th>
                                                                <th width="10%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>

                                            @endif
                                        @endif
                                    </div>
                                    @if(in_array(Auth::guard('web')->user()->campus, ['MC']))
                                        @if($queueMode->statusqueue === 'Off')
                                        @else
                                            <div class="col-md-3">
                                                <div class="form-group mt-2" style="padding: 10px">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card" style="background-color: #e9ecef">
                                                                <div class="card-body">
                                                                    <center><label>Current No.</label></center>
                                                                    <input type="text" id="queueNumber" class="form-control text-bold" readonly style="border: none; font-size: 20pt; text-align: center;">
                                                                    <button id="nextButton" class="btn btn-success mt-3" data-counter-id="1">Next</button> 
                                                                    <button id="callButton" class="btn btn-outline-danger mt-3">Call</button>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding: 10px">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card" style="background-color: #e9ecef">
                                                                <div class="card-body">
                                                                    <center><label>Select Transactions:</label></center>
                                                                    <form action="{{ route('counterUserUpdate') }}" method="POST" id="transacCategory">
                                                                        @csrf
                                                                        <input type="hidden" name="id" value="{{ $queueUser->id ?? '' }}" hidden>
                                                                        <select name="category" class="form-control" id="transacCategory">
                                                                            <option value="Enrollment" {{ (isset($queueUser) && $queueUser->category == 'Enrollment') ? 'selected' : '' }}>Enrollment</option>
                                                                            <option value="Processing" {{ (isset($queueUser) && $queueUser->category == 'Processing') ? 'selected' : '' }}>Evaluation</option>
                                                                            <option value="Pre-register" {{ (isset($queueUser) && $queueUser->category == 'Pre-register') ? 'selected' : '' }}>Pre-register</option>
                                                                        </select>
                                                                        <button type="submit" class="btn btn-outline-success btn-block mt-3">Save</button> 
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
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

        var selectQueueCatRoute  = "{{ route('counterUserUpdate') }}";
        var studqueuelistReadRoute = "{{ route('studqueuefetch') }}";
        var studqueuelistShowReadRoute = "{{ route('editsearchStudRead') }}";
    </script>
@endsection
