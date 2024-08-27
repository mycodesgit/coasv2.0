@php
    $curr_route = request()->route()->getName();

    $dashCashActive = in_array($curr_route, ['cashiering-index']) ? 'active' : '';
    $orActive = in_array($curr_route, ['list_orRead', 'listsearch_orRead']) ? 'active' : '';
    $orEditActive = in_array($curr_route, ['listedit_orRead', 'listsearchedit_orRead']) ? 'active' : '';
    $orallActive = in_array($curr_route, ['listall_orRead']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('cashiering-index') }}" class="list-group-item {{ $dashCashActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('list_orRead') }}" class="list-group-item {{ $orActive }}">Official Receipt</a>  
        <a href="{{ route('listedit_orRead') }}" class="list-group-item {{ $orEditActive }}">Edit Official Receipt</a>  
        {{-- <a href="{{ route('listall_orRead') }}" class="list-group-item {{ $orallActive }}">Official Receipt List</a>   --}}
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        {{-- <a href="{{ route('stateaccntpersem') }}" class="list-group-item {{ $studStateAccntActive }}">Accounts Per Semester</a> --}}
    </ul>
</div>