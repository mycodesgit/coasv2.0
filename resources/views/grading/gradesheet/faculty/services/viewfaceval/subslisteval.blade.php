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
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-chalkboard-teacher"></i> Faculty Evaluation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    @if(optional($collegedean)->facCollege == "CAS")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facdivisionchair as $datafacdivisionchair)
                                                @if($disabledsubj->contains($datafacdivisionchair->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacdivisionchair->lname }}, {{ collect(explode(' ', $datafacdivisionchair->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacdivisionchair->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacdivisionchair->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacdivisionchair->designation ??  'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacdivisionchair->subjID, 'qcefacID'  => $datafacdivisionchair->facID, 'qcefacname'  => $datafacdivisionchair->fname . ' ' . $datafacdivisionchair->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacdivisionchair->lname }}, {{ collect(explode(' ', $datafacdivisionchair->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacdivisionchair->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacdivisionchair->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacdivisionchair->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($casdivisionchair)->facCollege == "CAS")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegedean as $datafacollegedean)
                                                @if($disabledsubj->contains($datafacollegedean->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ??  'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegedean->subjID, 'qcefacID'  => $datafacollegedean->facID, 'qcefacname'  => $datafacollegedean->fname . ' ' . $datafacollegedean->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ??  'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegeprogramhead)->facCollege == "CAS")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegedean)->facCollege == "CAF")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegedean as $datafacollegedean)
                                                @if($disabledsubjdean->contains($datafacollegedean->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ??  'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegedean->subjID, 'qcefacID'  => $datafacollegedean->facID, 'qcefacname'  => $datafacollegedean->fname . ' ' . $datafacollegedean->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ??  'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegeprogramhead)->facCollege == "CAF")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegedean)->facCollege == "CBM")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegedean)->facCollege == "CCS")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegedean)->facCollege == "CJE")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($collegedean)->facCollege == "CTE")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($facollegedean as $datafacollegedean)
                                                @if($disabledsubj->contains($datafacollegedean->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegedean->subjID, 'qcefacID'  => $datafacollegedean->facID, 'qcefacname'  => $datafacollegedean->fname . ' ' . $datafacollegedean->lname, 'qceevaluator' => 'Dean']) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegedean->lname }}, {{ collect(explode(' ', $datafacollegedean->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegedean->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegedean->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegedean->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach

                                            @foreach($facollegeprogramhead as $datafacollegeprogramhead)
                                                @if($disabledsubj->contains($datafacollegeprogramhead->facID))
                                                    <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                                    <h3>Faculties:</h3>
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datafacollegeprogramhead->subjID, 'qcefacID'  => $datafacollegeprogramhead->facID, 'qcefacname'  => $datafacollegeprogramhead->fname . ' ' . $datafacollegeprogramhead->lname, 'qceevaluator' => 'Program Head']) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datafacollegeprogramhead->lname }}, {{ collect(explode(' ', $datafacollegeprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datafacollegeprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datafacollegeprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datafacollegeprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "VC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "SCC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "HC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "MP")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "IC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "CC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "CA")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "SC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($campusad)->campus == "HinC")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($campusadprogramhead as $datacampusadprogramhead)
                                                @if($disabledsubj->contains($datacampusadprogramhead->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $datacampusadprogramhead->subjID, 'qcefacID'  => $datacampusadprogramhead->facID, 'qcefacname'  => $datacampusadprogramhead->fname . ' ' . $datacampusadprogramhead->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $datacampusadprogramhead->lname }}, {{ collect(explode(' ', $datacampusadprogramhead->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($datacampusadprogramhead->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $datacampusadprogramhead->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $datacampusadprogramhead->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif(optional($vicepres)->id == "434")
                                        @if($setevalmode->statuseval === 'Off')
                                            <div class="col-12">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                    <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                    <div>
                                                        Faculty Evaluation is currently unavailable. Please check back later.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            <h3>Program Head:</h3>
                                            @foreach($viceprescampusad as $dataviceprescampusad)
                                                @if($disabledsubj->contains($dataviceprescampusad->facID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" >
                                                                <div class="card-body p-4" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $dataviceprescampusad->lname }}, {{ collect(explode(' ', $dataviceprescampusad->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($dataviceprescampusad->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $dataviceprescampusad->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $dataviceprescampusad->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('supfacevalrate', ['id' => $dataviceprescampusad->subjID, 'qcefacID'  => $dataviceprescampusad->facID, 'qcefacname'  => $dataviceprescampusad->fname . ' ' . $dataviceprescampusad->lname, 'qceevaluator' => 'Vice President']) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h5">{{ $dataviceprescampusad->lname }}, {{ collect(explode(' ', $dataviceprescampusad->fname))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('') }} {{ substr($dataviceprescampusad->mname, 0, 1) }}.</h3>
                                                                            <span>{{ $dataviceprescampusad->rank ?? 'Part-time' }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-user fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                {{ $dataviceprescampusad->designation ?? 'Faculty' }}
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                <div>
                                                    Waiting for Supervisor Evaluation
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
@endsection
