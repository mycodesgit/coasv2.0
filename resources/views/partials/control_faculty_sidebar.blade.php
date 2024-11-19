@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('homefaculty') }}" class="list-group-item {{ $dashSchActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1"> 
        <a href="{{ route('grades') }}" class="list-group-item">Grading</a>
        <a href="" class="list-group-item">Student List</a>
    </ul>
</div>