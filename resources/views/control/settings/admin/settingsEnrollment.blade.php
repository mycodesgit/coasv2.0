@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">Enrollment Status Control</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Enrollment Status Control</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <form method="post" action="{{ route('toggle.enrollment') }}" id="enrollForm">
                                            @csrf

                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <div class="icheck-success">
                                                                <input type="checkbox" id="enrollmode" name="statusenroll" data-url="{{ route('toggle.enrollment') }}" {{ $enrollMode->statusenroll === 'On' ? 'checked' : '' }}>
                                                                <label for="enrollmode">
                                                                    <h3 style="margin-top: -5px">Enrollment Status</h3>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5><i class="icon fas fa-exclamation-triangle text-warning mt-3"></i> Note!</h5>
                                                    <span>Check the checkbox if you want to <span style="font-weight: bold">"Start / Open"</span> the Enrollment for Undergraduate Students and uncheck if you will <span style="font-weight: bold">"Stop / CLose"</span>.</span>
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
        document.getElementById('enrollmode').addEventListener('change', function () {
            let isChecked = this.checked;
            let url = this.dataset.url;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ statusenroll: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //alert(data.message); // Optional: Show a confirmation alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Enrollment Status:',
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
