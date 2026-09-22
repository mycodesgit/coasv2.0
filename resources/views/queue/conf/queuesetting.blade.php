@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Queueing
@endsection

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
                            <li class="breadcrumb-item mt-1">Queueing</li>
                            <li class="breadcrumb-item active mt-1">Settings</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Settings</h1>
                        <p class="text-muted small mb-0">Manage queueing, on/off and reset queueing numbers in every window.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-settings"></i> Queueing Settings Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <form method="post" action="{{ route('toggle.queue') }}" id="queueForm">
                                            @csrf

                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <div class="icheck-success">
                                                                <input type="checkbox" id="queue" name="statusqueue" data-url="{{ route('toggle.queue') }}" {{ $setqueuemode->statusqueue === 'On' ? 'checked' : '' }}>
                                                                <label for="queue">
                                                                    <h3 style="margin-top: -5px">Queueing Mode</h3>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5><i class="icon fas fa-exclamation-triangle text-warning mt-3"></i> Note!</h5>
                                                    <span>Check the checkbox if you want to <span style="font-weight: bold">"On"</span> the queueing.</span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                        <form method="post" action="{{ route('queue.reset') }}" id="queueReset">
                                            @csrf
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <button type="button" id="resetButton" class="btn btn-outline-danger">
                                                                <i class="fas fa-refresh"></i> Reset Queueing
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <h5><i class="icon fas fa-exclamation-triangle text-warning mt-3"></i> Note!</h5>
                                                    <span>Resetting the queue will clear all current queue numbers. This action cannot be undone.</span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('queue').addEventListener('change', function () {
            let isChecked = this.checked;
            let url = this.dataset.url;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ statusqueue: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //alert(data.message); // Optional: Show a confirmation alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Queueing Mode',
                        text: data.message,
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: 'An error occurred!',
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });


    </script>
@endsection
