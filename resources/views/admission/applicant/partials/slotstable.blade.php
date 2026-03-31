@php use Carbon\Carbon; @endphp

@foreach ($groupedSlots as $date => $slots)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5>Admission Date: {{ Carbon::parse($date)->format('F d, Y') }}</h5>
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
                                <span class="badge bg-success">{{ $booked }}</span>
                                /
                                <span class="badge bg-secondary">{{ $slot->slots }}</span>
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
@endforeach