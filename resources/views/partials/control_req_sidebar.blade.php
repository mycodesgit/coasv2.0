@php
    $curr_route = request()->route()->getName();

    $docslistActive = in_array($curr_route, ['request-index']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        @auth('web')
            @if(Auth::guard('web')->user()->role == '0')
                <a href="{{ route('request-index') }}" class="list-group-item {{ $docslistActive }}">Add Document</a>  
            @endif
        @endauth
        <a href="#" class="list-group-item">Pending Request</a>
        <a href="#" class="list-group-item">Process Request</a>
        <a href="#" class="list-group-item">Approved Request</a>
    </ul>
</div>