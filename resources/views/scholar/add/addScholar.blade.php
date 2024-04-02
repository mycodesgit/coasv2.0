@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || Add Scholar
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">Add</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header">
            <form method="POST" action="{{ route('scholarCreate') }}" id="scholarAdd">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Add Scholarship</h4>
                </div>

                <div class="container mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Scholarship Name</span></label>
                                <input type="text" name="scholar_name" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Scholarship Sponsor</span></label>
                                <input type="text" name="scholar_sponsor" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Scholarship Category</span></label>
                                <select class="form-control form-control-sm" name="scholar_category">
                                    <option disabled selected>---Select---</option>
                                    <option value="CHED Scholarship">CHED Scholarship</option>
                                    <option value="Financial Assistance">Financial Assistance</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Funding Source</span></label>
                                <select class="form-control  form-control-sm" name="fund_source">
                                    <option disabled selected>---Select---</option>
                                    <option value="External">External</option>
                                    <option value="Internal">Internal</option>
                                    <option value="None">None</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Add
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
