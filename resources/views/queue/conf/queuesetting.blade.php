@extends('layouts.master_queue')

@section('title')
CISS V.1.0 || Queueing Setting
@endsection

@section('sideheader')
<h4>Queueing</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Queueing</li>
            <li class="breadcrumb-item active mt-1">Queueing Setting</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('toggle.queue') }}" id="queueForm">
                        @csrf

                        <div class="alert alert-secondary alert-dismissible">
                            <div class="form-group mt-3">
                                <div class="form-row">
                                    <div class="col-8">
                                        <div class="icheck-warning">
                                            <input type="checkbox" id="queue" name="statusqueue" data-url="{{ route('toggle.queue') }}" {{ $setqueuemode->statusqueue === 'On' ? 'checked' : '' }}>
                                            <label for="queue">
                                                <h3 style="margin-top: -5px">Queueing Mode</h3>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5><i class="icon fas fa-exclamation-triangle text-warning"></i>Note!</h5>
                            <span class="text-warning">Check the checkbox if you want to <span style="color: #fff">"On"</span> the queueing</span>
                        </div>
                    </form>

                    <form method="post" action="{{ route('queue.reset') }}" id="queueReset">
                        @csrf
                        <div class="alert alert-secondary alert-dismissible">
                            <div class="form-group mt-3">
                                <div class="form-row">
                                    <div class="col-8">
                                        <button type="button" id="resetButton" class="btn btn-danger btn-lg">
                                            <i class="fas fa-refresh"></i> Reset Queueing
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <h5><i class="icon fas fa-exclamation-triangle text-warning"></i>Note!</h5>
                            <span class="text-warning">Resetting the queue will clear all current queue numbers. This action cannot be undone.</span>
                        </div>
                    </form>
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

@section('script')