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
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Final Grade Submission</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> Select Semester & A.Y. for Final Grade Submission 
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-hover styled-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>A.Y.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach($progen as $dataprogen)
                                            <tr>
                                                <td>
                                                    <span style="color: transparent">{{ $no++ }}</span>
                                                    @if($dataprogen->semester == 1)
                                                        1st Semester
                                                    @elseif($dataprogen->semester == 2)
                                                        2nd Semester
                                                    @elseif($dataprogen->semester == 3)
                                                        Summer
                                                    @else
                                                        Unknown Semester
                                                    @endif
                                                </td>
                                                <td>{{ $dataprogen->schlyear }}</td>
                                                <td>
                                                    <a href="{{ route('virtualfaculty_class', ['semester' => $dataprogen->semester, 'schlyear' => $dataprogen->schlyear]) }}" type="button" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
