@extends('layouts.master_track')

@section('title')
CISS V.1.0 || Reset Password
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
                    @php
                        date_default_timezone_set('Asia/Manila');

                        $now = now();
                        $openingDate = \Carbon\Carbon::create(2026, 5, 25, 0, 0, 0, 'Asia/Manila');
                        $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                        $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                    @endphp

                    @if($now->lt($openingDate) || !$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                        <div class="col-12">
                            <div class="alert alert-warning text-center mb-0">
                                <i class="fas fa-info-circle"></i> Password reset is only available on weekdays from 8:00 AM to 5:00 PM starting May 25, 2026.
                            </div>
                        </div>
                    @else
                        @if (request()->routeIs('forgot.index'))
                        <div class="col-md-12">
                        @else
                        <div class="col-md-8">
                        @endif
                            <div class="card card-animate">
                                <div class="card-body p-4">
                                    @if(Session::has('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ Session::get('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @elseif (Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ Session::get('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- STEP 1: VERIFY STUDENT -->
                                    @if(!isset($student) && !isset($reset_token))
                                        <form method="POST" action="{{ route('reset-password.verify-student') }}">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="email">Email Address: <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email" 
                                                        placeholder="Enter Registered Email" 
                                                        class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                                        value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="stud_id">Student ID: <span class="text-danger">*</span></label>
                                                    <input type="text" name="stud_id" id="stud_id" 
                                                        placeholder="Enter Student ID (e.g., 2022-0001-1)" 
                                                        class="form-control form-control-sm @error('stud_id') is-invalid @enderror" 
                                                        value="{{ old('stud_id') }}" 
                                                        oninput="formatInput(this); this.value = this.value.toUpperCase()" 
                                                        onkeydown="handleDelete(event)" required>
                                                    @error('stud_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-success btn-block btn-sm text-light w-100">
                                                        <i class="fas fa-search"></i> Verify Student
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- STEP 2: SHOW STUDENT INFO & SEND OTP -->
                                    @if(isset($student) && !isset($reset_token))
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-10 col-lg-8">
                                                <!-- Success Animation / Icon -->
                                                <div class="text-center mb-4">
                                                    <div class="avatar-lg mx-auto mb-3">
                                                        <div class="avatar-title bg-success bg-opacity-10 rounded-circle">
                                                            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                                                        </div>
                                                    </div>
                                                    <h4 class="mb-1">Student Verified!</h4>
                                                    <p class="text-muted mb-0">Please confirm your identity to reset password</p>
                                                </div>

                                                <form method="POST" action="{{ route('reset-password.send-otp') }}" id="sendOtpForm">
                                                    @csrf
                                                    <input type="hidden" name="stud_id" value="{{ $student->stud_id }}">
                                                    <input type="hidden" name="email" value="{{ $student->email }}">
                                                    
                                                    <!-- Security Notice -->
                                                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                                                        <div class="d-flex">
                                                            <i class="fas fa-shield-alt me-2 mt-1"></i>
                                                            <div>
                                                                <strong>Security Notice:</strong> A One-Time Password (OTP) will be sent to your registered email address. 
                                                                The OTP is valid for <strong>15 minutes</strong> only.
                                                            </div>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Action Buttons - Responsive Stack -->
                                                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between align-items-center">
                                                        <button type="submit" class="btn btn-primary w-100 w-sm-auto order-1 order-sm-2" id="sendOtpBtn">
                                                            <i class="fas fa-paper-plane me-2"></i> Send OTP to Email
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Additional Info -->
                                                    <div class="text-center mt-4">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i> OTP expires in 15 minutes
                                                        </small>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <style>
                                            /* Custom responsive styles */
                                            @media (max-width: 576px) {
                                                .avatar-lg {
                                                    width: 80px;
                                                    height: 80px;
                                                }
                                                .avatar-title i {
                                                    font-size: 36px !important;
                                                }
                                                .card-body {
                                                    padding: 1.25rem !important;
                                                }
                                                .btn {
                                                    font-size: 14px;
                                                    padding: 8px 12px;
                                                }
                                            }
                                            
                                            @media (min-width: 768px) and (max-width: 1024px) {
                                                .avatar-lg {
                                                    width: 100px;
                                                    height: 100px;
                                                }
                                            }
                                            
                                            /* Hover effects */
                                            .btn-primary {
                                                transition: all 0.3s ease;
                                            }
                                            .btn-primary:hover {
                                                transform: translateY(-2px);
                                                box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
                                            }
                                            .btn-outline-secondary:hover {
                                                transform: translateY(-2px);
                                            }
                                            
                                            /* Card animation */
                                            .card {
                                                animation: fadeInUp 0.5s ease;
                                            }
                                            
                                            @keyframes fadeInUp {
                                                from {
                                                    opacity: 0;
                                                    transform: translateY(20px);
                                                }
                                                to {
                                                    opacity: 1;
                                                    transform: translateY(0);
                                                }
                                            }
                                        </style>

                                        <!-- JavaScript for loading state -->
                                        <script>
                                            document.getElementById('sendOtpForm')?.addEventListener('submit', function(e) {
                                                const submitBtn = document.getElementById('sendOtpBtn');
                                                const originalText = submitBtn.innerHTML;
                                                
                                                submitBtn.disabled = true;
                                                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending OTP...';
                                                
                                                // Re-enable button after 30 seconds if form doesn't submit (fallback)
                                                setTimeout(function() {
                                                    if (submitBtn.disabled) {
                                                        submitBtn.disabled = false;
                                                        submitBtn.innerHTML = originalText;
                                                    }
                                                }, 30000);
                                            });
                                        </script>
                                    @endif

                                    <!-- STEP 3: VERIFY OTP & RESET PASSWORD -->
                                    @if(isset($reset_token) && isset($student))
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <h2 class="fs-4"><i class="ti ti-checklist"></i> Password Requirements</h2>
                                                    <hr>
                                                    <ul class="small">
                                                        <li>Minimum 5 characters</li>
                                                        <li>At least 1 uppercase letter (A-Z)</li>
                                                        <li>At least 4 number (0-9)</li>
                                                        <li>System generated password</li>
                                                    </ul>
                                                    <div class="alert alert-warning mt-2">
                                                        <i class="fas fa-shield-alt"></i> 
                                                        <strong>Security Notice:</strong> After resetting, you'll be logged out from all devices.
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('reset-password.update') }}" id="resetPasswordForm">
                                                    @csrf
                                                    <input type="hidden" name="stud_id" value="{{ $student->stud_id }}">
                                                    <input type="hidden" name="token" value="{{ $reset_token }}">
                                                    
                                                    <div class="row g-3">
                                                        <!-- OTP Field -->
                                                        <div class="col-12 col-md-6">
                                                            <label for="otp">One-Time Password (OTP): <span class="text-danger">*</span></label>
                                                            <input type="text" name="otp" id="otp" 
                                                                placeholder="Enter 6-digit OTP sent to your email" 
                                                                class="form-control form-control-sm @error('otp') is-invalid @enderror" 
                                                                maxlength="6" pattern="[0-9]{6}" required>
                                                            <small class="text-muted">OTP expires in 15 minutes</small>
                                                            @error('otp')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        
                                                        <!-- New Password Field with Generate Button -->
                                                        <div class="col-12 col-md-6">
                                                            <label for="new_password">New Password: <span class="text-danger">*</span></label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="password" name="new_password" id="new_password" 
                                                                    placeholder="Minimum 8 characters with 1 uppercase, 1 number, 1 special char" 
                                                                    class="form-control form-control-sm @error('new_password') is-invalid @enderror" 
                                                                    required readonly onkeydown="return false;">
                                                                <button type="button" class="btn btn-warning btn-sm" id="autogeneratePassword" title="Generate Strong Password">
                                                                    <i class="fas fa-sync-alt"></i> Generate
                                                                </button>
                                                                <button type="button" class="btn btn-secondary btn-sm" id="togglePassword" title="Show/Hide Password">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </div>
                                                            <div id="passwordStrength" class="small mt-1"></div>
                                                            <small class="text-muted">Or click "Generate" for a strong password</small>
                                                            @error('new_password')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        
                                                        <!-- Confirm Password Field -->
                                                        <div class="col-12 col-md-6">
                                                            <label for="confirm_password">Confirm New Password: <span class="text-danger">*</span></label>
                                                            <input type="password" name="confirm_password" id="confirm_password" 
                                                                class="form-control form-control-sm" required readonly onkeydown="return false;">
                                                            <small id="passwordMatch" class="small"></small>
                                                        </div>
                                                        
                                                        <!-- Submit Button -->
                                                        <div class="col-12 col-md-6">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm w-100" id="submitReset">
                                                                <i class="fas fa-key"></i> Reset Password
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                
                                                <!-- Action Links -->
                                                <div class="mt-3 d-flex flex-wrap gap-2">
                                                    <a href="{{ route('forgot.index') }}" class="btn btn-link btn-sm">
                                                        <i class="fas fa-redo"></i> Start Over
                                                    </a>
                                                    <button type="button" class="btn btn-link btn-sm" onclick="resendOTP()">
                                                        <i class="fas fa-envelope"></i> Resend OTP
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- STUDENT INFORMATION CARD -->
                        @if(isset($student))
                            <div class="col-md-4">
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
                                                                <th>Student ID:</th>
                                                                <td>{{ $student->stud_id ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Full Name:</th>
                                                                <td>{{ $student->fname ?? '' }} {{ $student->lname ?? '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Email:</th>
                                                                <td>{{ substr($student->email ?? '', 0, 3) }}***{{ substr($student->email ?? '', strpos($student->email ?? '', '@') - 2) }}{{ substr($student->email, strpos($student->email, '@')) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Course/Year:</th>
                                                                <td>{{ $student->course ?? 'N/A' }} - {{ $student->year_level ?? 'N/A' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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

@endsection