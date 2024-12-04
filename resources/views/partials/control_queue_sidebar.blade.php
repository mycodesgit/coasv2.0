@php
    $curr_route = request()->route()->getName();

    $countersActive = in_array($curr_route, ['queue-index']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="" class="list-group-item {{ $countersActive }}">Counters</a>
    </ul>
</div>