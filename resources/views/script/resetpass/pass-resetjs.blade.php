<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    // Format Student ID: XXXX-XXXX-X
    function formatInput(input) {
        let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');
        if (cleaned.length > 0) {
            let formatted = cleaned.substring(0, 4);
            if (cleaned.length > 4) formatted += '-' + cleaned.substring(4, 8);
            if (cleaned.length > 8) formatted += '-' + cleaned.substring(8, 9);
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

    // ============ PASSWORD GENERATOR FUNCTION ============
    function generateStrongPassword() {
        // Your original format (digits + letter suffix)
        var digits = String(Math.floor(Math.random() * 10000)).padStart(4, '0'); 
        var suffix = ['K', 'U', 'G', 'X', 'Z']; 
        var char = suffix[Math.floor(Math.random() * suffix.length)]; 
        var simplePassword = digits + char;
        
        return simplePassword;
    }
    
    // Alternative: Generate a more secure password with all requirements
    // ============ PASSWORD GENERATOR FUNCTION ============
    function generateStrongPassword() {
        // Generate 4 random digits (0000-9999)
        var digits = String(Math.floor(Math.random() * 10000)).padStart(4, '0'); 
        
        // Only use these 3 uppercase letters
        var suffix = ['K', 'U', 'G']; 
        var char = suffix[Math.floor(Math.random() * suffix.length)]; 
        
        // Combine digits + letter
        var simplePassword = digits + char;
        
        return simplePassword;
    }
    
    // Copy password to clipboard function
    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Password copied to clipboard',
                toast: true,
                position: 'toast-top-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Failed to copy',
                text: 'Please manually copy the password',
                toast: true,
                position: 'toast-top-right',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
    
    // Auto-generate password on button click
    $('#autogeneratePassword').click(async function() {
        var generatedPassword = generateStrongPassword();
        $('#new_password').val(generatedPassword);
        $('#confirm_password').val(generatedPassword);
        
        // Trigger password strength checker
        $('#new_password').trigger('input');
        $('#confirm_password').trigger('input');
        
        // Show SweetAlert with generated password
        const result = await Swal.fire({
            title: 'Password Generated!',
            html: `
                <div class="text-center">
                    <p class="mb-2">Your new password is:</p>
                    <div class="alert alert-success">
                        <strong style="font-size: 20px; letter-spacing: 2px;">${generatedPassword}</strong>
                    </div>
                    <small class="text-muted">Please save this password or copy it now.</small>
                </div>
            `,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-copy"></i> Copy Password',
            cancelButtonText: 'OK',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        });
        
        if (result.isConfirmed) {
            copyToClipboard(generatedPassword);
        }
    });
    
    // Toggle password visibility
    let passwordVisible = false;
    $('#togglePassword').click(function() {
        const passwordInput = $('#new_password');
        const icon = $(this).find('i');
        
        if (passwordVisible) {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
            passwordVisible = false;
        } else {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
            passwordVisible = true;
        }
    });
    
    // Password strength checker
    document.getElementById('new_password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthDiv = document.getElementById('passwordStrength');
        
        let strength = 0;
        let messages = [];
        
        if (password.length >= 8) {
            strength++;
        } else {
            messages.push('Minimum 8 characters');
        }
        
        if (password.match(/[A-Z]/)) {
            strength++;
        } else {
            messages.push('At least 1 uppercase letter');
        }
        
        if (password.match(/[a-z]/)) {
            strength++;
        } else {
            messages.push('At least 1 lowercase letter');
        }
        
        if (password.match(/[0-9]/)) {
            strength++;
        } else {
            messages.push('At least 1 number');
        }
        
        if (password.match(/[!@#$%^&*]/)) {
            strength++;
        } else {
            messages.push('At least 1 special character (!@#$%^&*)');
        }
        
        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            strengthDiv.className = '';
        } else if (strength <= 2) {
            strengthDiv.innerHTML = '⚠️ Weak password: ' + messages.join(', ');
            strengthDiv.className = 'text-danger';
        } else if (strength <= 4) {
            strengthDiv.innerHTML = '⚠️ Medium password: ' + messages.join(', ');
            strengthDiv.className = 'text-warning';
        } else {
            strengthDiv.innerHTML = '✓ Strong password!';
            strengthDiv.className = 'text-success';
        }
    });

    // Password match checker
    document.getElementById('confirm_password')?.addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirm = this.value;
        const matchDiv = document.getElementById('passwordMatch');
        
        if (confirm.length === 0) {
            matchDiv.innerHTML = '';
        } else if (password === confirm) {
            matchDiv.innerHTML = '✓ Passwords match';
            matchDiv.className = 'text-success';
        } else {
            matchDiv.innerHTML = '✗ Passwords do not match';
            matchDiv.className = 'text-danger';
        }
    });

    // Resend OTP function with SweetAlert
    async function resendOTP() {
        // Show loading confirmation
        const confirmResend = await Swal.fire({
            title: 'Resend OTP?',
            text: 'A new One-Time Password will be sent to your registered email.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Yes, Resend',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d'
        });
        
        if (!confirmResend.isConfirmed) {
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Sending OTP...',
            text: 'Please wait while we send a new code to your email',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = new FormData();
        formData.append('stud_id', '{{ $student->stud_id ?? '' }}');
        formData.append('email', '{{ $student->email ?? '' }}');
        
        try {
            const response = await fetch('{{ route("reset-password.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Resent!',
                    text: 'A new OTP has been sent to your email. Valid for 15 minutes.',
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Resend',
                    text: data.message || 'Please try again later.',
                    confirmButtonColor: '#dc3545'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to send OTP. Please refresh the page and try again.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    // Form submission validation with SweetAlert
    document.getElementById('resetPasswordForm')?.addEventListener('submit', async function(e) {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password !== confirm) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Your new password and confirmation password do not match!',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK, Fix it'
            });
            return false;
        }
        
        // Check password strength requirements
        const strength = (password.length >= 5 && password.match(/[A-Z]/) && password.match(/[0-9]/));
        
        if (!strength) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Weak Password',
                html: `
                    <div class="text-start">
                        <p>Your password does not meet security requirements:</p>
                        <ul class="text-start">
                            <li>Minimum 5 characters</li>
                            <li>At least 1 uppercase letter (A-Z)</li>
                            <li>At least 4 number (0-9)</li>
                            <li>System generated password</li>
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'I understand'
            });
            return false;
        }
        
        // Show confirmation before reset
        e.preventDefault(); // Prevent default to show confirmation first
        
        const confirmReset = await Swal.fire({
            title: 'Reset Password?',
            text: 'Are you sure you want to change your password? You will need to login with the new password.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check"></i> Yes, Reset',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        });
        
        if (confirmReset.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Resetting Password...',
                text: 'Please wait while we update your password',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            this.submit();
        }
        
        return false;
    });

    // Remove any conflicting event listeners and use this instead
    document.getElementById('sendOtpForm')?.addEventListener('submit', function(e) {
        // Don't prevent default - let the form submit normally
        const submitBtn = document.getElementById('sendOtpBtn');
        const originalText = submitBtn.innerHTML;
        
        // Only change button state, don't prevent submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending OTP...';
        
        // Re-enable button after 30 seconds if something goes wrong
        setTimeout(function() {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 30000);
        
        // Let the form submit normally - no e.preventDefault()
    });
    
    // Success message handler (check if session success exists)
    @if(Session::has('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ Session::get('success') }}',
        confirmButtonColor: '#28a745',
        timer: 5000,
        timerProgressBar: true
    });
    @endif
    
    // Error message handler
    @if(Session::has('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ Session::get('error') }}',
        confirmButtonColor: '#dc3545',
        timer: 5000,
        timerProgressBar: true
    });
    @endif
</script>