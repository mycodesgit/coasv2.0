@extends('layouts.master_apply')

@section('title')
CISS V.1.0 || Apply Admission
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}

                <div class="row g-3 mb-3 mt-1">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            {{-- Top decorative accent bar --}}
                            <div class="bg-success" style="height: 6px;"></div>
                            <div class="card-body p-4 p-md-5 text-center">
                                {{-- Success Icon --}}
                                <div class="mb-4">
                                    <span class="fa-stack fa-2x">
                                        <i class="fas fa-circle fa-stack-2x text-success opacity-25"></i>
                                        <i class="fas fa-check fa-stack-1x text-success"></i>
                                    </span>
                                </div>
                                {{-- Heading & Messages --}}
                                <h2 class="text-success fw-bold mb-3">Thank You!</h2>
                                <h4 class="fw-semibold text-dark mb-3">Your Documents was uploaded successfully.</h4>
                                <p class="text-muted mb-4 fs-6">
                                    We have received your application. Please check your email inbox (and spam folder) for a copy of your submission details.
                                </p>

                                {{-- Optional Manual Trigger Form (if auto-send isn't handled by controller) --}}
                                <form method="post" action="{{ route('sendThankYouEmail') }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ old('email', $email) }}">
                                    <input type="hidden" name="year" value="{{ old('year', $year) }}">

                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admission-portal') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                            Return to Portal
                                        </a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let historyLock = setInterval(() => {
            history.pushState(null, null, location.href);
        }, 100);

        setTimeout(() => {
            Swal.fire({
                title: 'We received your application!',
                text: 'Thank you for applying.',
                icon: 'success',
                confirmButtonText: 'Okay'
            }).then((result) => {
                if (result.isConfirmed) {
                    clearTimeout(autoSendEmailTimeout);

                    const emailInput = document.querySelector('input[name="email"]');
                    if (emailInput) {
                        const email = emailInput.value;
                        sendEmail(email);
                    } else {
                        console.error('Email input not found');
                    }
                }
            });

            // Set a timeout to automatically send the email if "Okay" is not clicked within 5 seconds
            let autoSendEmailTimeout = setTimeout(() => {
                const emailInput = document.querySelector('input[name="email"]');
                if (emailInput) {
                    const email = emailInput.value;

                    sendEmail(email);
                } else {
                    console.error('Email input not found');
                }
            }, 2000);
        }, 2000);

        // Function to send the email
        function sendEmail(email, year) {
            const emailInput = document.querySelector('input[name="email"]');
            const yearInput  = document.querySelector('input[name="year"]');

            if (!email && emailInput) {
                email = emailInput.value;
            }
            if (!year && yearInput) {
                year = yearInput.value;
            }

            fetch('{{ route('sendThankYouEmail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email,  year: year })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
@endpush
