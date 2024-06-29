@extends('layouts.master_kiosk')

@section('body')
<div class="container">
    <div class="row" style="padding-top: 0px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-2 row">
                        <div class="col-md-12 mb-3">
                            <h4 class="card-footer" style="border-radius: 5px">{{ $studauth->stud_id }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; {{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="tab-content" id="vert-tabs-right-tabContent">
                                <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel" aria-labelledby="vert-tabs-right-one-tab">
                                    <div class="card-body table-responsive p-0" style="height: 500px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Descriptive Title</th>
                                                    <th>School Year</th>
                                                    <th>Final Grade</th>
                                                    <th>Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($studsub as $datastudsubowner)
                                                <tr>
                                                    <td>{{ $datastudsubowner->sub_name }}</td>
                                                    <td>{{ $datastudsubowner->sub_title }}</td>
                                                    <td>{{ $datastudsubowner->schlyear }}</td>
                                                    <td><b>{{ $datastudsubowner->subjFgrade }}</b></td>
                                                    <td>{{ $datastudsubowner->creditEarned }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                                    sdsdsd
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card" style="background-color: #e9ecef !important">
                                <div class="ml-2 mr-2 mt-3 mb-1">
                                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                        
                                    </div>
                                    <div class="mt-3" style="font-size: 13pt;">
                                        <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">View Grades</a>
                                            <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="true">View Account</a>
                                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                                        </div>
                                    </div>
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