@php
    $curr_route = request()->route()->getName();

    $kioskadminSchActive = in_array($curr_route, ['adminkioskRead']) ? 'active' : '';
    $kioskbulkSchActive = in_array($curr_route, ['adminbulkkioskRead', 'adminbulkkioskShow']) ? 'active' : '';
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('adminkioskRead') }}" class="list-group-item {{ $kioskadminSchActive }}">Kiosk User</a>
        <a href="{{ route('adminbulkkioskRead') }}" class="list-group-item {{ $kioskbulkSchActive }}">Bulk Generation</a>
    </ul>
</div>