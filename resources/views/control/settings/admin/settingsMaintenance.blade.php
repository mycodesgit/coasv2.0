@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Server Maintenance
@endsection

@section('sideheader')
<h4>Settings</h4>
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
            <li class="breadcrumb-item mt-1">Settings</li>
            <li class="breadcrumb-item active mt-1">Server Maintenance</li>
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
                    <form method="post" action="{{ route('toggleMaintenance') }}" id="">
                        @csrf

                        <div class="alert alert-secondary alert-dismissible">
                            <div class="form-group mt-3">
                                <div class="form-row">
                                    <div class="col-8">
                                        <div class="icheck-warning">
                                            <input type="checkbox" id="maintenance" name="maintenance_mode"  {{ $maintenance_mode ? 'checked' : '' }}>
                                            <label for="maintenance">
                                                <h3 style="margin-top: -5px">Maintenance Mode</h3>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5><i class="icon fas fa-exclamation-triangle text-warning"></i>Note!</h5>
                            <span class="text-warning">Check the checkbox if you want a server maintenance</span>
                        </div>

                        @auth('web')
                            @if(Auth::guard('web')->user()->isAdmin == '0')
                            <div class="form-group mt-2">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')