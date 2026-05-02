@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Profile
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Profile</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Profile
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3 bg-light p-2 rounded-2 d-inline-flex" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-information-tab" data-bs-toggle="pill" data-bs-target="#pills-information" type="button" role="tab" aria-controls="pills-information" aria-selected="true">Information</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab" aria-controls="pills-history" aria-selected="false" tabindex="-1">Enrollment History</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="pills-tabContent">
                                    <div class="tab-pane fade active show" id="pills-information" role="tabpanel" aria-labelledby="pills-information-tab" tabindex="0">
                                        <form> 
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label for="fnameID" class="form-label">Student ID No.</label>
                                                    <input type="text" name="stud_id" class="form-control" value="{{ $studauth->stud_id }}" id="fnameID" readonly>
                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="fnameID" class="form-label">First Name</label>
                                                    <input type="text" name="fname" class="form-control" value="{{ $studauth->fname }}" id="fnameID" readonly>
                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="mnameID" class="form-label">Middle Name</label>
                                                    <input type="text" name="mname" class="form-control" value="{{ $studauth->mname }}" id="mnameID" readonly>
                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="lnameID" class="form-label">Last Name</label>
                                                    <input type="text" name="lname" class="form-control" value="{{ $studauth->lname }}" id="lnameID" readonly>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab" tabindex="0">
                                        <div class="row g-3 mb-4">
                                            <div class="table-responsive">
                                                <table class="table table-head-fixed text-nowrap" style="font-size: 10pt">
                                                    <thead>
                                                        <tr>
                                                            <th>School Year</th>
                                                            <th>Semester</th>
                                                            <th>Program</th>
                                                            <th>Year Level</th>
                                                            <th>Section</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($studproghistory as $itemstudproghistory)
                                                            <tr>
                                                                <td>{{ $itemstudproghistory->schlyear }}</td>
                                                                <td>
                                                                    @if ($itemstudproghistory->semester == 1)
                                                                        <span class="badge bg-success">1st Sem</span>
                                                                    @elseif ($itemstudproghistory->semester == 2)
                                                                        <span class="badge bg-warning">2nd Sem</span>
                                                                    @elseif ($itemstudproghistory->semester == 3)
                                                                        <span class="badge bg-secondary">Summer</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $itemstudproghistory->progAcronym }}</td>
                                                                <td>{{ $itemstudproghistory->studYear }}</td>
                                                                <td>{{ $itemstudproghistory->studSec }}</td>
                                                            </tr>
                                                        @endforeach
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
        </div>
    </div>
@endsection
