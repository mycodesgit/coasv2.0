@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
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
                    <span class="text-muted">/ Faculty Evaluation</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-chalkboard-teacher"></i> Faculty Evaluation
                                </h6>
                            </div>
                            <div class="card-body">
                                {{-- <form method="GET" action="" id="enrollStud">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear" id="schlyeardean">
                                                    @foreach($currsem as $datacurrsem)
                                                        <option value="{{ $datacurrsem->qceschlyear }}">{{ $datacurrsem->qceschlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label>Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester" id="semesterdean">
                                                    @foreach ($currsem as $datacurrsem)
                                                        <option value="{{ $datacurrsem->qcesemester }}">
                                                            @if($datacurrsem->qcesemester == 1)
                                                                1st Semester
                                                            @elseif($datacurrsem->qcesemester == 2)
                                                                2nd Semester
                                                            @elseif($datacurrsem->qcesemester == 3)
                                                                Summer
                                                            @else
                                                                {{ $datacurrsem->qcesemester }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Faculty: <span class="text-danger">*</label>
                                                <select class="form-control form-control-sm select2bs4" name="faclty" id="faclty">
                                                    <option disabled selected> --Select--- </option>
                                                    @foreach ($facdivisionchair as $itemfacdivisionchair)
                                                        <option value="{{ $itemfacdivisionchair->id }}">{{ $itemfacdivisionchair->lname }}, {{ $itemfacdivisionchair->fname }} {{ substr($itemfacdivisionchair->mname, 0, 1) }}.</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </form> --}}
                                <div class="row g-3">
                                    @foreach($facdivisionchair as $datafacdivisionchair)
                                        <div class="col-lg-3 col-12">
                                            <a href="{{ route('supfacevalrate', ['id' => $datafacdivisionchair->subjID, 'qcefacID'  => $datafacdivisionchair->facID, 'qcefacname'  => $datafacdivisionchair->fname . ' ' . $datafacdivisionchair->lname]) }}">
                                                <div class="card card-hover h-100">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex justify-content-between pb-5 mb-3">
                                                            <div>
                                                                <h3 class="fw-bold h5">{{ $datafacdivisionchair->lname }}, {{ collect(explode(' ', $datafacdivisionchair->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacdivisionchair->mname, 0, 1) }}.</h3>
                                                                <span>{{ $datafacdivisionchair->rank }}</span><br>
                                                                <span style="font-size: 9pt;">
                                                                    <span class="text-success">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <i class="ti ti-user fs-1 text-success"></i>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center small">
                                                            <div class="text-muted">
                                                                <span class="text-dark">
                                                                    {{ $datafacdivisionchair->designation }}
                                                                </span>
                                                            </div>
                                                            <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
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

    
@endsection
