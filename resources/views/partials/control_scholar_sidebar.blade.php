@php
    $curr_route = request()->route()->getName();

    $addScholarActive = in_array($curr_route, ['scholarAdd']) ? 'active' : '';
    $listScholarActive = in_array($curr_route, ['chedscholarRead', 'chedscholarSearch']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('scholarAdd') }}" class="list-group-item {{ $addScholarActive }}">Add Scholarship</a>  
        <a href="{{ route('chedscholarRead') }}" class="list-group-item {{ $listScholarActive }}">Scholarship List</a>
        <a href="" class="list-group-item">Student Scholarship</a>
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="" class="list-group-item">Students Scholar Reports</a>
    </ul>
</div>