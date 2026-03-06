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
    <style>
        #table {
                margin-top: 10px;
                font-family: Arial;
                border-collapse: collapse;
                width: 100%;
                border: 1px solid #000;
            }
            #table td {
                vertical-align: center !important;
                text-align: left;
                border: 1px solid #000;
                font-size: 12pt;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial,
                sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
            } 
            #table th {
                font-size: 13pt;
                border: 1px solid #000;
                padding: 5px;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial,
                sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
            }
            @media (max-width: 768px) {
                #table th {
                    font-size: 11pt;
                }
                #table td {
                    font-size: 10pt;
                }
            }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/</span>
                    <a href="{{ route('supfaceval') }}">
                        Faculties
                    </a>
                    <span class="text-muted">/ Evaluation Now</span>
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
                                <form action="{{ route('deanfacevalrateformCreate') }}" method="POST" id="evaluation-form">
                                    @csrf

                                    <input type="hidden" name="campus" value="{{ $facdetail->campus ?? '' }}">
                                    <input type="hidden" name="qceschlyearsemID" value="{{ $currsem->first()->id }}">
                                    <input type="hidden" name="schlyear" value="{{ $currsem->first()->qceschlyear }}">
                                    <input type="hidden" name="semester" value="{{ $currsem->first()->qcesemester }}">
                                    <input type="hidden" name="qcefacID" value="{{ request('qcefacID') }}">
                                    <input type="hidden" name="evaluatorname" value="{{ Auth::guard('faculty')->user()->fname }} {{ substr(Auth::guard('faculty')->user()->mname ?? '', 0, 1) }} {{ Auth::guard('faculty')->user()->lname }}">
                                    <input type="hidden" name="evaluatorID" value="{{ Auth::guard('faculty')->user()->id }}">
                                    <input type="hidden" name="studidno" value="{{ Auth::guard('faculty')->user()->id }}">
                                    <input type="hidden" name="prog" value="{{ $facdetail->faccollege ?? '' }}">
                                    <input type="hidden" name="qceevaluator" value="{{ $facDesignateRole->designation }}">

                                    <div class="row mb-1">
                                        <div id="card-1" class="row g-2 mb-2">
                                            <div class="col-md-4 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Rating Period: <span class="text-danger">*</span></label>
                                                        <input type="text" name="ratingfromto" class="form-control textbold required-input" placeholder="Rating Period" value="{{ $currsem->first()->qceratingfrom }} - {{ $currsem->first()->qceratingto }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Name of Faculty: <span class="text-danger">*</span></label>
                                                        <input type="text" name="qcefacname" class="form-control textbold required-input" placeholder="Name of Faculty" value="{{ $facdetail->fname ?? '' }} {{ substr($facdetail->mname ?? '', 0, 1) }} {{ $facdetail->lname ?? '' }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Academic Rank: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control textbold required-input" placeholder="Academic Rank" value="{{ $facdetail->rank ?? 'Part-time' }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="card-2" style="display: none;">
                                            <div class="card mb-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2">
                                                <div class="card-body">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <label>Name of Faculty: <span class="text-danger">*</span></label>
                                                            <input type="text" name="qcefacname" class="form-control textbold" placeholder="Name of Faculty" value="{{ request('qcefacname') ?? '' }}" required readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Academic Rank: <span class="text-danger">*</span></label>
                                                            <input type="text" name="qcefacrank" class="form-control textbold" placeholder="Academic Rank" value="{{ $facdetail->rank ?? 'Part-time' }}" required readonly>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <table id="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="ratingscale" width="10%">Scale</th>
                                                                        <th class="ratingscale" width="28%">Qualitative Description</th>
                                                                        <th>Operational Definition</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($ratingscale as $dataratingscale)
                                                                        <tr>
                                                                            <td class="ratingscaletd" style="text-align: center; font-weight: bold">{{ $dataratingscale->inst_scale }}</td>
                                                                            <td class="ratingscaletd" style="text-align: center; font-weight: normal; width: 188px">{!! $dataratingscale->inst_descRating !!}</td>
                                                                            <td class="ratingscaletd">{{ $dataratingscale->inst_qualDescription }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @php $no = 1; @endphp
                                            @foreach ($question as $catName => $questions)
                                                <div class="card bg-success bg-opacity-10 mb-1" style="">
                                                    <div class="card-body">
                                                        <h4 class="category-title">{{ $catName }}</h4>
                                                    </div>
                                                </div>
                                                @foreach($questions as $dataformlinksquestions)
                                                    <input type="hidden" class="required-input" name="question[]" value="{{ $dataformlinksquestions->id }}">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-bold">
                                                                {{ $no++ }}.  {{ $dataformlinksquestions->questiontext }}
                                                            </h5>
                                                            <p class="card-text mt-5"></p>
                                                            <div class="radio-group" style="margin-top: 5px">
                                                                @for ($i = 5; $i >= 1; $i--)
                                                                    <a href="#" class="card-link text-dark">
                                                                        <input type="radio" class="required-input" id="radio-{{ $i }}-{{ $dataformlinksquestions->id }}" name="question_rate[{{ $dataformlinksquestions->id }}]" value="{{ $i }}" required>
                                                                        <label for="radio-{{ $i }}-{{ $dataformlinksquestions->id }}">{{ $i }}</label>
                                                                    </a>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach

                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Comments: <span class="text-danger">*</span></label>
                                                        <textarea name="qcecomments" class="form-control" id="" cols="30" rows="10"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                        <button type="button" class="btn btn-default" id="back-btn" onclick="prevCard(currentCard - 1)" style="display: none;">Back</button>
                                        <button type="button" class="btn btn-success" id="next-btn" onclick="nextCard(currentCard + 1)" style="display: none;">Next</button>
                                        {{-- <button type="button" class="btn btn-info" id="ok-btn">OK</button> --}}
                                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">Submit</button>

                                        <div class="progress-container d-flex align-items-center">
                                            <div class="progress" style="width: 60%; margin-right: 10px; background-color: gray; border-radius: 20px;">
                                                <div id="progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span id="progress-text">Page 1 of <span id="total-pages"></span></span>
                                        </div>

                                        <a href="#" onclick="clearForm()" class="btn btn-default" style="color: #5e5df0; text-decoration: underline;">Clear</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
@endsection
