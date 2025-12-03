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
                            <h4>Attendance Sheet <span style="font-size: 12pt; font-style: italic;" class="text-danger">(Click icon to view Attendance Sheet)</span></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            @php
                                $colors = ['red', 'green', 'lightblue', 'yellow', 'gray'];  // Colors for the folder icons
                                $fixedColors = ['skyblue', 'green', 'orange', 'yellow', 'gray'];  // Set fixed colors for folders 1 to 10
                            @endphp

                            <div class="row mt-2">
                                @foreach($datafacsubprogen as $index => $attendfac)
                                    @php
                                        $randomColor = ($index < 10) ? $fixedColors[$index % count($fixedColors)] : $colors[array_rand($colors)];
                                    @endphp
                                    <div class="col-6 col-sm-4 col-md-2 text-center mb-3">
                                        <h2>
                                            <a href="{{ route('attendance_searchfacpdfpage', ['id' => $attendfac->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}" class="text-dark">
                                            <i class="fa-regular fa-file-lines folder-icon" aria-hidden="true" style="color: {{ $randomColor }}; font-size: 60px;"></i>
                                            <br>
                                            <span style="color: {{ $randomColor }}; font-size: 14px; display: inline-block; margin-top: 5px;"></span>
                                            <span style="font-size: 12px; font-weight: bold;">{{ $attendfac->sub_name }} - {{ $attendfac->subSec }}</span>
                                            </a>
                                        </h2>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <iframe id="" src="" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
