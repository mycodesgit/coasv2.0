@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || Cashiering
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Cashier</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Cashier Dashboard Overview</h1>
                        <p class="text-muted small mb-0">Student payment, daily transactions logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">₱{{ number_format($todayCollect, 2) }}</h3>
                                        <span>Collections for Today</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-coins fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">₱{{ number_format($monthCollect, 2) }}</h3>
                                        <span>Collections for Month</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-calendar-check fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $todayClients }}</h3>
                                        <span>No. of Clients Catered Today</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $monthClients }}</h3>
                                        <span>No. of Clients Catered this Month</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Secondary Section: Recent Transactions & Quick Actions -->
                <div class="row g-3">
                    <!-- Recent Activities / Transactions Table -->
                    <div class="col-lg-9 col-12">
                        <div class="card card-animate h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h6 class="card-title">Recent Payment Transactions</h6>
                                <a href="#" class="text-decoration-none small">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">OR Number</th>
                                                <th>Student Name</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th class="text-end pe-3">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Example Loop: replace $recentTransactions with your controller data --}}
                                            @forelse($recentTransactions ?? [] as $tx)
                                                <tr>
                                                    <td class="ps-3 fw-semibold">{{ $tx->or_number }}</td>
                                                    <td>{{ $tx->student_name }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $tx->payment_type }}</span></td>
                                                    <td>₱{{ number_format($tx->amount, 2) }}</td>
                                                    <td class="text-end pe-3 text-muted small">{{ $tx->created_at->format('h:i A') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        <i class="ti ti-receipt-off fs-3 d-block mb-1"></i>
                                                        No transactions recorded for today yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Panel -->
                    <div class="col-lg-3 col-12">
                        <div class="card card-animate h-100">
                            <div class="card-header bg-transparent py-3">
                                <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                            </div>
                            <div class="card-body d-flex flex-column gap-2">
                                <a href="#" class="btn btn-outline-success text-start py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span><i class="ti ti-cash-register me-2 fs-5"></i> New Student Payment</span>
                                    <i class="ti ti-chevron-right small"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success text-start py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span><i class="ti ti-search me-2 fs-5"></i> Verify Student Account</span>
                                    <i class="ti ti-chevron-right small"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success text-start py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span><i class="ti ti-file-analytics me-2 fs-5"></i> Print Daily Cash Breakdown</span>
                                    <i class="ti ti-chevron-right small"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success text-start py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span><i class="ti ti-history me-2 fs-5"></i> View Shift Logs</span>
                                    <i class="ti ti-chevron-right small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
