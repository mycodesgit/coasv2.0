@php
    $curr_route = request()->route()->getName();

    $docslistActive = in_array($curr_route, ['request-index']) ? 'active' : '';
    $cwtslistActive = in_array($curr_route, ['cwts_nstp', 'cwts_nstpresult']) ? 'active' : '';
    $ltslistActive = in_array($curr_route, ['lts_nstp', 'lts_nstpresult']) ? 'active' : '';
    $rotclistActive = in_array($curr_route, ['rotc_nstp', 'rotc_nstpresult']) ? 'active' : '';
    $reportsNSTPlistActive = in_array($curr_route, ['reports_nstp', 'reports_nstpresult']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="#" class="list-group-item">Dashboard</a>
        <a href="{{ route('cwts_nstp') }}" class="list-group-item {{ $cwtslistActive }}">CWTS Students</a>
        <a href="{{ route('lts_nstp') }}" class="list-group-item {{ $ltslistActive }}">LTS Students</a>
        <a href="{{ route('rotc_nstp') }}" class="list-group-item {{ $rotclistActive }}">ROTC Students</a>
    </ul>
</div>

<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('reports_nstp') }}" class="list-group-item {{ $reportsNSTPlistActive }}">Generate</a>
    </ul>
</div>