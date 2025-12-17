@php
    $curr_route = request()->route()->getName();

    $dashnstplistActive = in_array($curr_route, ['nstp-index']) ? 'active' : '';
    $cwtslistActive = in_array($curr_route, ['cwts_nstp', 'cwts_nstpresult']) ? 'active' : '';
    $ltslistActive = in_array($curr_route, ['lts_nstp', 'lts_nstpresult']) ? 'active' : '';
    $rotclistActive = in_array($curr_route, ['rotc_nstp', 'rotc_nstpresult']) ? 'active' : '';
    $gradenstpActive = in_array($curr_route, ['gradenstp', 'gradenstp_searchlist', 'gradenstpview']) ? 'active' : '';
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('nstp-index') }}" class="list-group-item {{ $dashnstplistActive }}">Dashboard</a>
    </ul>

    <ul class="list-group mt-1">
        <a href="{{ route('cwts_nstp') }}" class="list-group-item {{ $cwtslistActive }}">CWTS Students</a>
        <a href="{{ route('lts_nstp') }}" class="list-group-item {{ $ltslistActive }}">LTS Students</a>
        <a href="{{ route('rotc_nstp') }}" class="list-group-item {{ $rotclistActive }}">ROTC Students</a>
        <a href="{{ route('gradenstp') }}" class="list-group-item {{ $gradenstpActive }}">Gradesheet</a>
    </ul>
</div>
