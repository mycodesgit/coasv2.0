@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Home
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="card">
        <div class="card-body">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
            <div class="workspace-top" style="text-align: center;">
                <h1 class="fas fa-mug-hot fa-7x" style="color: #04401f"></h1>
                <h1><span style="color:#ffff66;font-size: 70px;">Eey!</span> Grab a coffee before doing something.</h1>
                <p>  <i class="fas fa-quote-left fa-2x fa-pull-left"></i>
                    Gatsby believed in the green light, the orgastic future that year by year recedes before us.
                    It eluded us then, but that’s no matter — tomorrow we will run faster, stretch our arms further...
                    And one fine morning — So we beat on, boats against the current, borne back ceaselessly into the past.
                </p>
                <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
            </div>
        </div>
    </div>
@endsection