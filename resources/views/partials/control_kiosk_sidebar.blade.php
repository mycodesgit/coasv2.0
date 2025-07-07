@php
    $curr_route = request()->route()->getName();

    $kioskadminSchActive = in_array($curr_route, ['adminkioskRead']) ? 'active' : '';
    $kioskbulkSchActive = in_array($curr_route, ['adminbulkkioskRead', 'adminbulkkioskShow']) ? 'active' : '';
    $kioskreportschActive = in_array($curr_route, ['kioskReport']) ? 'active' : '';
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('adminkioskRead') }}" class="list-group-item {{ $kioskadminSchActive }}">Kiosk User</a>
        <a href="{{ route('adminbulkkioskRead') }}" class="list-group-item {{ $kioskbulkSchActive }}">Bulk Generation</a>
    </ul>
    @if(Auth::guard('web')->user()->campus == 'MC')
        <div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
            <h5>Reports</h5>
        </div>
        <div class="ml-2 mr-2 mt-3 mb-3">
            <ul class="list-group">
                <a href="{{ route('kioskReport') }}" class="list-group-item {{ $kioskreportschActive }}">Kiosk Reports</a>
            </ul>
        </div>
    @endif
</div>