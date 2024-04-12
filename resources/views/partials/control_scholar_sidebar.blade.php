@php
    $curr_route = request()->route()->getName();

    $addScholarActive = in_array($curr_route, ['scholarAdd']) ? 'active' : '';
    $chedScholarActive = in_array($curr_route, ['chedscholarlist']) ? 'active' : '';
    $uniScholarActive = in_array($curr_route, ['unischolarlist']) ? 'active' : '';
    $allScholarActive = in_array($curr_route, ['scholarlist']) ? 'active' : '';
    $listStudScholarActive = in_array($curr_route, ['chedstudscholarRead', 'studscholar_searchRead']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('scholarAdd') }}" class="list-group-item {{ $addScholarActive }}">Add Scholarship</a>  
        <a href="{{ route('chedscholarlist') }}" class="list-group-item {{ $chedScholarActive }}">CHED Scholarship</a>
        <a href="{{ route('unischolarlist') }}" class="list-group-item {{ $uniScholarActive }}">CPSU Scholarship</a>
        <a href="{{ route('scholarlist') }}" class="list-group-item {{ $allScholarActive }}">Scholarship</a>
        <a href="{{ route('chedstudscholarRead') }}" class="list-group-item {{ $listStudScholarActive }}">Students</a>
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