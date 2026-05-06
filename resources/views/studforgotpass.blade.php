@extends('layouts.master_track')

@section('title')
CISS V.1.0 || Track Admission
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('main') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active mt-1">Reset Password</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-body p-4">
                                @if(Session::has('success'))
                                    <div class="alert alert-success">{{ Session::get('success')}}</div>
                                @elseif (Session::has('error'))
                                    <div class="alert alert-danger">{{Session::get('error')}}</div>
                                @endif
                                <form method="POST" action="">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="emailstud">Email: <span class="text-danger">*</span></label>
                                            <input type="text" name="lname" placeholder="Enter Last Name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="lastname">Last Name: <span class="text-danger">*</span></label>
                                            <input type="text" name="lname" placeholder="Enter Last Name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="firstname">First Name: <span class="text-danger">*</span></label>
                                            <input type="text" name="fname" placeholder="Enter First Name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="searchbutton">&nbsp;</label>
                                            <button type="submit" class="btn btn-success btn-block btn-sm text-light">
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body p-4">
                                <div>
                                    <h2 class="fs-4"><i class="ti ti-user"></i> Student Information</h2>
                                    <hr>
                                    <div class="mt-4">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Name:</th>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date of Birth:</th>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body p-4">
                                <div>
                                    <h2 class="fs-4"><i class="ti ti-checklist"></i> Request Reset Password</h2>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
