@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
@endsection

@yield('sidemenu')

@section('workspace')
    @php
        $authUser = Auth::guard('web')->user();

        // Campus map
        $campusMap = [
            'MC'   => 'Main Campus',
            'VC'   => 'Victorias Campus',
            'SCC'  => 'San Carlos Campus',
            'HC'   => 'Hinigaran Campus',
            'MP'   => 'Moises Padilla Campus',
            'IC'   => 'Ilog Campus',
            'CA'   => 'Candoni Campus',
            'CC'   => 'Cauayan Campus',
            'SC'   => 'Sipalay Campus',
            'HinC' => 'Hinobaan Campus',
        ];

        // Role map
        $roleMap = [
            0  => 'Administrator',
            1  => 'Guidance Officer',
            2  => 'Guidance Staff',
            3  => 'Registrar',
            4  => 'Registrar Staff',
            5  => 'College Dean',
            6  => 'Program Head',
            7  => 'College Staff',
            8  => 'Scholarship Head',
            9  => 'Scholarship Staff',
            10 => 'Assessment Head',
            11 => 'Assessment Staff',
            12 => 'MIS Staff',
            13 => 'MIS Director',
            14 => 'MIS Officer',
            15 => 'Graduate School Staff',
            16 => 'OSSA Staff',
            17 => 'Cashier',
            18 => 'Cashier Staff',
            19 => 'Encoder',
            20 => 'Dean of Instruction',
            21 => 'YearBook',
        ];

        // Access URL map
        $accessMap = [
            'admission-url'  => 'Admission',
            'enrollment-url' => 'Enrollment',
            'scheduler-url'  => 'Scheduler',
            'assessment-url' => 'Assessment',
            'kiosk-url'      => 'Kiosk',
            'queue-url'      => 'Queue System',
            'setting-url'    => 'Settings',
        ];

        $userRoleLabel   = $roleMap[$authUser->role] ?? ($authUser->role ?? 'User');
        $userCampusLabel = $campusMap[$authUser->campus] ?? ($authUser->campus ?? '-');
        $isAccountActive = (int)$authUser->statuser === 1;

        // Grab the buttons column directly (Support Option A or Option B)
        $rawButtons = $buttonAccess->buttons ?? $user->buttons ?? [];

        // Safety fallback in case it comes through as a string or array
        if (is_string($rawButtons)) {
            $accessList = json_decode($rawButtons, true) ?? [];
        } else {
            $accessList = (array) $rawButtons;
        }
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- Navigation Breadcrumb --}}
                <div class="card" style="background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">Accounts</li>
                        </ol>
                    </div>
                </div>

                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Accounts Information</h4>
                                </div>
                                <div class="row g-4 mt-1">
                                    
                                    {{-- Left Profile Card --}}
                                    <div class="col-12 col-lg-4 text-center">
                                        <div class="card h-100 bg-light-subtle shadow-sm">
                                            <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" 
                                                        alt="User Avatar" 
                                                        class="rounded-circle img-fluid border border-3 border-success p-1 shadow-sm" 
                                                        style="width: 130px; height: 130px; object-fit: cover;">
                                                </div>

                                                <h5 class="fw-bold mb-1 text-dark">
                                                    {{ $authUser->fname }} {{ $authUser->mname }} {{ $authUser->lname }} {{ $authUser->ext }}
                                                </h5>
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-user-tag me-1 text-success"></i>{{ $userRoleLabel }}
                                                </p>

                                                <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                                                    <a href="#" class="badge bg-success text-white px-3 py-2 rounded-pill fw-normal text-decoration-none">
                                                        <i class="fas fa-lock me-1"></i>Change Password
                                                    </a>
                                                    
                                                    @if($isAccountActive)
                                                        <span class="badge bg-outline-success border border-success text-success px-3 py-2 rounded-pill fw-normal">
                                                            <i class="fas fa-check-circle me-1"></i>Active Account
                                                        </span>
                                                    @else
                                                        <span class="badge bg-outline-danger border border-danger text-danger px-3 py-2 rounded-pill fw-normal">
                                                            <i class="fas fa-times-circle me-1"></i>Inactive Account
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="w-100 bg-white border rounded p-2 text-truncate small text-secondary">
                                                    <i class="fas fa-envelope text-success me-2"></i>{{ $authUser->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Details Panel --}}
                                    <div class="col-12 col-lg-8">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body p-4">
                                                
                                                {{-- Personal Information --}}
                                                <div class="mb-4">
                                                    <h6 class="fw-bold text-uppercase border-bottom pb-2 fs-7">
                                                        <i class="fas fa-user me-2"></i>Personal Information
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-6 col-sm-3">
                                                            <small class="text-muted d-block mb-1">First Name</small>
                                                            <span class="fw-semibold text-dark">{{ $authUser->fname ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <small class="text-muted d-block mb-1">Middle Name</small>
                                                            <span class="fw-semibold text-dark">{{ $authUser->mname ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <small class="text-muted d-block mb-1">Last Name</small>
                                                            <span class="fw-semibold text-dark">{{ $authUser->lname ?? '-' }}</span>
                                                        </div>
                                                        <div class="col-6 col-sm-3">
                                                            <small class="text-muted d-block mb-1">Extension Name</small>
                                                            <span class="fw-semibold text-dark">{{ $authUser->ext ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Academic Details --}}
                                                <div class="mb-4">
                                                    <h6 class="fw-bold text-uppercase border-bottom pb-2 fs-7">
                                                        <i class="fas fa-university me-2"></i>Academic & Institutional Details
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-12 col-sm-6">
                                                            <small class="text-muted d-block mb-1">Campus</small>
                                                            <span class="fw-semibold text-dark">{{ $userCampusLabel }}</span>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <small class="text-muted d-block mb-1">Department</small>
                                                            <span class="fw-semibold text-dark">{{ $authUser->dept ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Account & Access Details --}}
                                                <div>
                                                    <h6 class="fw-bold text-uppercase border-bottom pb-2 fs-7">
                                                        <i class="fas fa-user-shield me-2"></i>Account & Access Details
                                                    </h6>
                                                    <div class="row g-3">
                                                        {{-- User Role --}}
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <small class="text-muted d-block mb-1">User Role</small>
                                                            <span class="badge bg-light text-dark border fw-normal">{{ $userRoleLabel }}</span>
                                                        </div>

                                                        {{-- Account Status --}}
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <small class="text-muted d-block mb-1">Account Status</small>
                                                            <span class="fw-semibold text-dark">
                                                                @if($isAccountActive)
                                                                    <span class="text-success"><i class="fas fa-circle fs-8 me-1"></i></span>Active
                                                                @else
                                                                    <span class="text-danger"><i class="fas fa-circle fs-8 me-1"></i></span>Inactive
                                                                @endif
                                                            </span>
                                                        </div>

                                                        {{-- Account Access Badges --}}
                                                        <div class="col-12 col-md-6">
                                                            <small class="text-muted d-block mb-1">Account Access</small>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @forelse($accessList as $item)
                                                                    <span class="badge bg-light text-dark border fw-normal">
                                                                        <i class="fas fa-check-circle text-success me-1"></i>{{ $accessMap[$item] ?? ucfirst(str_replace('-url', '', $item)) }}
                                                                    </span>
                                                                @empty
                                                                    <span class="text-muted small">No access permissions granted.</span>
                                                                @endforelse
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
                </div>
            </div>
        </div>
    </div>
@endsection