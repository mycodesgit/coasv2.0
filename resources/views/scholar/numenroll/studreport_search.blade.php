@extends('layouts.master_scholarship')

@section('title')
CISS V.1.0 || List of Scholars Student
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
            <li class="breadcrumb-item active mt-1">Student Scholarship</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studenscholarreport_searchRead') }}" id="studscholar">
                @csrf

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
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
        <div class="mt-2">
            <div class="table-responsive">
                <table id="studreportscholar" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>StudID</th>
                            <th>Birthday</th>
                            <th>Address</th>
                            <th>Street/Barangay</th>
                            <th>Municipality/City</th>
                            <th>Province</th>
                            <th>Region</th>
                            <th>Zipcode</th>
                            <th>Course</th>
                            <th>YearLevel</th>
                            <th>Scholarship</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($data as $schstud)
                            <tr>
                                <td>{{ $schstud->lname }}, {{ $schstud->fname }}</td>
                                <td>{{ $schstud->studentID }}</td>
                                <td>{{ $schstud->progAcronym }} {{ $schstud->studYear }}-{{ $schstud->studSec }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->scholar_name }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->scholar_sponsor }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->chedsch_name }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->unisch_name }}</td>
                                <td>{{ number_format($schstud->amount, 2)  }}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var studschreportReadRoute = "{{ route('getStudScholarReportRead') }}";
</script>

@endsection

@section('script')
