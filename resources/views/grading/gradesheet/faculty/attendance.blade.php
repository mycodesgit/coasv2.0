@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
@endsection

@yield('sidemenu')

@section('workspace')
    <section class="section mt-4">
        <!-- <div class="section-header" style="border-radius: 20px !important;">
            <h1>Blank Page</h1>
        </div> -->

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            <form method="GET" action="{{ route('attendance_searchfac') }}" id="attendancegrading">
                                @csrf   

                                <div class="form-group mt-2" style="padding: 10px">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label><span class="badge badge-success">School Year</span></label>
                                            <select class="form-control form-control-sm" name="schlyear">
                                                @foreach($sy as $datasy)
                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label><span class="badge badge-success">Semester</span></label>
                                            <select class="form-control form-control-sm" name="semester">
                                                <option disabled selected>Select</option>
                                                <option value="1">First Semester</option>
                                                <option value="2">Second Semester</option>
                                                <option value="3">Summer</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
