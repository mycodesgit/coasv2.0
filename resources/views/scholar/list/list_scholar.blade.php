@extends('layouts.master_scholarship')

@section('title')
COAS - V1.0 || Add Scholar
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
            <li class="breadcrumb-item active mt-1">List</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('chedscholarSearch') }}">
                @csrf

                <div class="container">
                    <div class="form-group">
                        <div class="form-row">
                            

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Category</span></label>
                                <select class="form-control form-control-sm" name="category">
                                    <option disabled selected>---Select---</option>
                                    <option value="CHED Scholarship">CHED Scholarship</option>
                                    <option value="Financial Assistance">Financial Assistance</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <div class="">
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Scholarship Name</th>
                            <th>Sponsor</th>
                            <th>Category</th>
                            <th>Funding Source</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
