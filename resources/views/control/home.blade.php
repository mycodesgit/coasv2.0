@extends('layouts.master')

@section('title')
    CISS V.1.0 || Home
@endsection

@section('sideheader')
    <h4></h4>
@endsection

@section('sidemenu')
    <div class="col-md-4">
        <div class="small-box" style="background-color: rgb(150, 207, 178)">
            <div class="inner p-4">
                <h4>VISION</h4>

                <p>CPSU as the leading technology-driven multi-disciplinary University by 2030.</p>
            </div>
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
        </div>

        <div class="small-box" style="background-color: rgb(87, 197, 144)">
            <div class="inner p-4">
                <h4>MISSION</h4>

                <p>CPSU is committed to produce competent graduates who can generate and extend leading
                technologies in multi-disciplinary areas beneficial to the community.</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrows-to-eye"></i>
            </div>
        </div>

        <div class="small-box" style="background-color: rgb(154, 240, 197)">
            <div class="inner p-4">
                <h4>GOAL</h4>

                <p>To provide efficient, Quality, Technology-driven and Gender-Sensitive Products and
                Services.</p>
            </div>
            <div class="icon">
                <i class="fas fa-bullseye"></i>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="small-box" style="background-color: #e6e6e6">
            <div class="inner p-4">
                <h4 style="font-size: 15pt">Grab a coffee before doing something.</h4>

                <p style="font-style: italic; text-align: left; font-size: 8pt"><i class="fas fa-quote-left fa-1x fa-pull-left"></i>
                    Gatsby believed in the green light, the orgastic future that year by year recedes before us.
                    It eluded us then, but that’s no matter tomorrow we will run faster, stretch our arms further...
                    And one fine morning, So we beat on, boats against the current, borne back ceaselessly into the past.
                </p>
            </div>
            <div class="icon">
                <i class="fas fa-mug-hot"></i>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Current Enrollment A.Y. 2025-2026, 2nd Semester -
                    @if (Auth::guard('web')->user()->campus == 'MC') Main Campus
                        @elseif(Auth::guard('web')->user()->campus == 'VC') Victorias Campus
                        @elseif(Auth::guard('web')->user()->campus == 'SCC') San Carlos Campus
                        @elseif(Auth::guard('web')->user()->campus == 'HC') Hinigaran Campus
                        @elseif(Auth::guard('web')->user()->campus == 'MP') Moises Padilla Campus
                        @elseif(Auth::guard('web')->user()->campus == 'IC') Ilog Campus
                        @elseif(Auth::guard('web')->user()->campus == 'CA') Candoni Campus
                        @elseif(Auth::guard('web')->user()->campus == 'CC') Cauayan Campus
                        @elseif(Auth::guard('web')->user()->campus == 'SC') Sipalay  Campus
                        @elseif(Auth::guard('web')->user()->campus == 'HinC') Hinobaan Campus
                        @elseif(Auth::guard('web')->user()->campus == 'VE') Valladolid Campus
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <canvas id="currentSemesterBarChart" style="height:330px; min-height:330px"></canvas>
            </div>
        </div>
    </div>
@endsection
