@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Class Schedule</span> 
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Class Schedule
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-head-fixed text-nowrap" style="font-size: 10pt">
                                            <thead>
                                                <tr>
                                                    <th>A.Y. Semester</th>
                                                    <th>Prog. Yr&Section</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($enrollmentHistory as $history)
                                                    <tr>
                                                        <td>{{ $history->schlyear }}
                                                            @if($history->semester == 1)
                                                                <span class="badge bg-primary">1st Sem</span>
                                                            @elseif($history->semester == 2)
                                                                <span class="badge bg-success">2nd Sem</span>
                                                            @elseif($history->semester == 3)
                                                                <span class="badge bg-secondary">Summer</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $history->progAcronym }} {{ $history->studYear }}-{{ $history->studSec }}</td>
                                                        <td>
                                                            <a href="{{ route('schedstudentclassShow', ['schlyear' => $history->schlyear, 'semester' => $history->semester, 'progCod' => $history->progCod.'+'.$history->studYear.'-'.$history->studSec]) }}" class="btn btn-outline-success btn-sm">
                                                                View Sched
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
    </div>
@endsection