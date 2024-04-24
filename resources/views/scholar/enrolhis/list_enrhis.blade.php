@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || Student Enrollment History
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">Student Enrollment History</li>
        </ol>
        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
        <div class="page-header mt-2">
            <form method="GET" action="{{ route('viewsearchStudHistory') }}" id="studscholar">
                @csrf

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Search Student Lastname or Student ID No.</span></label>
                                <input type="text" name="query" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" placeholder="Search Student Lastname or Student ID No." autofocus>
                            </div>


                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // document.getElementById('searchType').addEventListener('change', function() {
    //     var selectedOption = this.value;
    //     if (selectedOption === 'lname') {
    //         document.getElementById('searchLastName').style.display = 'block';
    //         document.getElementById('searchStudentID').style.display = 'none';
    //         document.getElementById('searchStudButton').style.display = 'block';
    //     } else if (selectedOption === 'studentID') {
    //         document.getElementById('searchLastName').style.display = 'none';
    //         document.getElementById('searchStudentID').style.display = 'block';
    //         document.getElementById('searchStudButton').style.display = 'block';
    //     } else {
    //         document.getElementById('searchLastName').style.display = 'none';
    //         document.getElementById('searchStudentID').style.display = 'none';
    //         document.getElementById('searchStudButton').style.display = 'none';
    //     }
    // });
</script>


@endsection

@section('script')
