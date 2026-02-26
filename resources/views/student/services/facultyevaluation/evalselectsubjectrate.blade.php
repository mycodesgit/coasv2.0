@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Services <span class="text-muted">/</span> Faculty Evaluation</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Subjects for Evaluation
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="">
                                    <div class="row mb-1">
                                        <div id="card-1" class="row g-2 mb-2">
                                            <div class="col-md-4 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Rating Period: <span class="text-danger">*</span></label>
                                                        <input type="text" name="ratingfromto" class="form-control required-input" placeholder="Rating Period" value="{{ $currsem->first()->qceratingfrom }} - {{ $currsem->first()->qceratingto }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Name of Faculty: <span class="text-danger">*</span></label>
                                                        <input type="text" name="facultyname" class="form-control required-input" placeholder="Name of Faculty" value="{{ $facdetail->first()->fname ?? '' }} {{ substr($facdetail->first()->mname ?? '', 0, 1) }} {{ $facdetail->first()->lname ?? '' }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Academic Rank: <span class="text-danger">*</span></label>
                                                        <input type="text" name="facultyname" class="form-control required-input" placeholder="Name of Faculty" value="{{ $facdetail->first()->rank ?? 'Part-time' }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="card-2" style="display: none;">
                                            <div class="col-md-12 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <label>Name of Faculty: <span class="text-danger">*</span></label>
                                                        <input type="text" name="qcefacname" class="form-control" placeholder="Name of Faculty" value="{{ $facdetail->first()->qcefacname ?? '' }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
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
                                                                {{ $loop->iteration }}.) {{ $dataformlinksquestions->questiontext }}
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

                                        <a href="#" onclick="clearForm()" class="btn btn-default" style="color: #5e5df0; text-decoration: underline;">Clear form</a>
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