@php
    $curr_route = request()->route()->getName();

    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active' : '';
    // $suboffActive = in_array($curr_route, ['subjectsOffered']) ? 'active' : '';
    // $subjectActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('grades') }}" class="list-group-item {{ $gradeActive }}">Grade Sheet</a>  
    </ul>
</div>