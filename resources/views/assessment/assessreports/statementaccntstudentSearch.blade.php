@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Student Statements of Accounts Summary
@endsection

@section('sideheader')
<h4>Assessment</h4>
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
            <li class="breadcrumb-item mt-1">Assessment</li>
            <li class="breadcrumb-item active mt-1">Student Statements of Accounts Summary</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Student Statements of Accounts Summary</h4>
        </div>

        <div class="mt-2 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('stateaccntperstudentid_search') }}" id="studstatesum">
                    @csrf

                    <div class="">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Search Type</span></label>
                                    <select class="form-control form-control-sm" id="searchType" onchange="toggleFields()">
                                        <option disabled selected> --Select-- </option>
                                        <option value="id">Student ID Number</option>
                                        <option value="name">Student Name</option>
                                    </select>
                                </div>

                                <!-- Student ID Number Field -->
                                <div class="col-md-2" id="idField" style="display:none;">
                                    <label><span class="badge badge-secondary">Student ID Number</span></label>
                                    <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()">
                                </div>

                                <!-- Student Name Fields -->
                                <div class="col-md-2" id="nameFieldLast" style="display:none;">
                                    <label><span class="badge badge-secondary">Student Lastname</span></label>
                                    <input type="text" name="lname" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                </div>

                                <div class="col-md-2" id="nameFieldFirst" style="display:none;">
                                    <label><span class="badge badge-secondary">Student Firstname</span></label>
                                    <input type="text" name="fname" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
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
        </div>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3 row">
            <div class="col-md-12">
                <table id="reportAssessUndergrad" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student ID Number</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentRoute = Route::currentRouteName();
                            $actionRoute = ($currentRoute == 'stateaccntperstudentid_search') 
                                           ? route('stateaccntperstudent_searchpdf') 
                                           : route('stateaccntperstudentname_searchpdf');
                        @endphp
                        @foreach($data as $datasumstudfeesen)
                            <tr>
                                <td>{{ $datasumstudfeesen->stud_id }}</td>
                                <td>{{ $datasumstudfeesen->lname }}, {{ $datasumstudfeesen->fname }}</td>
                                <td>{{ $datasumstudfeesen->progAcronym }}</td>
                                <td>
                                    <form action="{{ $actionRoute }}" method="GET" target="_blank">
                                        @csrf

                                        @if($currentRoute == 'stateaccntperstudentid_search')
                                            <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                        @endif

                                        @if($currentRoute == 'stateaccntperstudentname_search')
                                            <input type="hidden" name="stud_id" value="{{ $datasumstudfeesen->stud_id }}">
                                        @endif

                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

<script>
    function toggleFields() {
        var searchType = document.getElementById("searchType").value;
        
        if (searchType === "id") {
            document.getElementById("idField").style.display = "block";
            document.getElementById("nameFieldLast").style.display = "none";
            document.getElementById("nameFieldFirst").style.display = "none";
        } else if (searchType === "name") {
            document.getElementById("idField").style.display = "none";
            document.getElementById("nameFieldLast").style.display = "block";
            document.getElementById("nameFieldFirst").style.display = "block";
        }
    }
</script>


@endsection
