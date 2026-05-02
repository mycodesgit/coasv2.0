@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Pre-enrollment</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search for Pre-enrollment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12"> 
                                    <form method="GET" action="{{ route('prelist.process') }}" id="enrollStud" class="mb-3">
                                        @csrf 

                                        <div class="form-group mt-2">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label>Student ID Number: <span class="text-danger">*</span></label>
                                                    <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>School Year: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="schlyear">
                                                        @foreach($sy as $datasy)
                                                            <option value="{{ encrypt($datasy->schlyear) }}">{{ $datasy->schlyear }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Semester: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="semester">
                                                        @foreach($sy as $datasy)
                                                            <option value="{{ encrypt($datasy->semester) }}">{{ $datasy->semester == 1 ? '1st Semester' : ($datasy->semester == 2 ? '2nd Semester' : 'Summer') }}</option>
                                                        @endforeach
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
                                    @if(in_array(Auth::guard('faculty')->user()->campus, ['MC']))
                                        @if($queueMode->statusqueue === 'Off')

                                        @else
                                            <div class="mt-5">
                                                <h5>List of Pre-Enrolled Students</h5>
                                                <table id="holdTable" class="table table-hover">
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
    </script>
@endsection
