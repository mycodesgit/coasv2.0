@php
    $curr_route = request()->route()->getName();

    $countersActive = in_array($curr_route, ['queue-index']) ? 'active' : '';
    $numbersActive = in_array($curr_route, ['numberRead']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('queue-index') }}" class="list-group-item {{ $countersActive }}">Counters</a>
        <a href="{{ route('numberRead') }}" class="list-group-item {{ $numbersActive }}">Numbers</a>
    </ul>
</div>