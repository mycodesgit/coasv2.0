@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Profile
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Profile</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Account Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-4 text-center">
                                        <div class="mb-3">
                                            <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="avatar" class="rounded-circle img-fluid" style="width:140px;height:140px;object-fit:cover;">
                                        </div>
                                        <h5 class="mb-0">{{ $authfaculty->fname }} {{ $authfaculty->lname }}</h5>
                                        <p class="text-muted small">Faculty</p>
                                        {{-- <div class="d-flex justify-content-center gap-2 mt-2">
                                            <a href="" class="btn btn-sm btn-primary">Edit Profile</a>
                                            <a href="" class="btn btn-sm btn-outline-secondary">Change Password</a>
                                        </div> --}}
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <div class="row">
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Lastname:</small>
                                                <div>{{ $authfaculty->lname ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Firstname:</small>
                                                <div>{{ $authfaculty->fname ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Middlename:</small>
                                                <div>{{ $authfaculty->mname ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Extension:</small>
                                                <div>{{ $authfaculty->ext ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Prefix:</small>
                                                <div>-</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Suffix:</small>
                                                <div>-</div>
                                            </div>
                                            <hr>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">College:</small>
                                                <div>{{ $authfaculty->college_name ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Department:</small>
                                                <div>{{ $authfaculty->deptName ?? '-' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Email:</small>
                                                <div>{{ $authfaculty->email }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Position:</small>
                                                <div>Faculty</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Designation:</small>
                                                <div>{{ $authfaculty->designation ?? 'Not Designation' }}</div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-3">
                                                <small class="text-muted">Academic Rank:</small>
                                                <div>
                                                    {{ $authfaculty->rank ?? 'Not Specified' }}
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <h6>About</h6>
                                            <p class="text-muted small">-</p>
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
