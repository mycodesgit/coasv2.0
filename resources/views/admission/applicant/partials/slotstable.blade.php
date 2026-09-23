@php use Carbon\Carbon; @endphp

@foreach ($groupedSlots as $date => $slots)
<div class="col-12 col-md-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center pt-3">
            <h6 class="card-title">
                <i class="ti ti-calendar"></i> Admission Date: {{ Carbon::parse($date)->format('F d, Y') }}
            </h6>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                <i class="ti ti-circle-filled me-1 style-dot blink-dot"></i>Live
            </span>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Availability</th>
                        <th>Campus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($slots as $slot)
                        @php
                            $key = $date . '_' . $slot->time;
                            $booked = $bookings[$key]->total ?? 0;
                            $remaining = $slot->slots - $booked;
                            $campusName = match ($slot->timecampus) {
                                'MC' => 'Main',
                                'VC' => 'Victorias',
                                'SCC' => 'San Carlos',
                                'HC' => 'Hinigaran',
                                'MP' => 'Moises Padilla',
                                'IC' => 'Ilog',
                                'CA' => 'Candoni',
                                'CC' => 'Cauayan',
                                'SP' => 'Sipalay',
                                'HinC' => 'Hinobaan',
                                default => $slot->timecampus,
                            };
                        @endphp

                        <tr>
                            <td>{{ Carbon::parse($slot->time)->format('h:i A') }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success">{{ $booked }}</span>
                                |
                                <span class="badge bg-info-subtle text-info">{{ $slot->slots }}</span>
                                <small>({{ $remaining }} left)</small>
                            </td>
                            <td>
                                {{ $campusName }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
