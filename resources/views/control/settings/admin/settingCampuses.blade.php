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
                            <li class="breadcrumb-item active mt-1">Campuses Control</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Campuses Control <span style="font-size: 10pt; color: #000000">(Check to "Allow " the Student Login in specific Campus)</span></h4>
                                </div>
                                <div class="row g-3 mb-3 mt-3">
                                    @foreach ($campuses as $campus)
                                        <div class="col-lg-3 col-12">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="d-flex justify-content-between pb-2">
                                                            <div class="icheck-success">
                                                                <input 
                                                                    type="checkbox" 
                                                                    id="stdntlogin_{{ $campus->id }}"
                                                                    class="campus-toggle"
                                                                    data-id="{{ $campus->id }}"
                                                                    data-url="{{ route('toggle.campuses') }}"
                                                                    {{ $campus->login_enabled ? 'checked' : '' }}
                                                                >
                                                                <label for="stdntlogin_{{ $campus->id }}">
                                                                    <h3 style="margin-top: -5px">{{ $campus->name }}</h3>
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <i class="ti ti-login fs-1 text-info"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6>
                                                        <i class="icon fas fa-exclamation-circle text-warning mt-3"></i> 
                                                        <span class="text-muted">Check to </span><strong>"Allow "</strong>
                                                    </h6>
                                                    <span class="text-muted"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.campus-toggle').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                let isChecked = this.checked; // true or false
                let campusId = this.dataset.id;
                let url = this.dataset.url;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: campusId,
                        login_enabled: isChecked // always boolean
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        text: data.message
                    });
                })
                .catch(err => console.error(err));
            });
        });
    </script>
@endsection
