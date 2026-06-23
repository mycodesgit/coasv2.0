@php
    $isDone = $disabledsubj->contains($faculty->facID);
    $cardStyle = $isDone ? 'background-color: rgba(230, 230, 230, 0.644)' : '';
    $badgeClass = $isDone ? 'bg-success' : 'bg-info';
    $badgeIcon = $isDone ? 'ti ti-check' : 'ti ti-x';
    $badgeText = $isDone ? 'Done Evaluate' : 'Not Done';
    $route = $isDone ? '#' : route('supfacevalrate', [
        'id' => $faculty->subjID ?? $faculty->id,
        'qcefacID' => $faculty->facID ?? $faculty->id,
        'qcefacname' => $faculty->fname . ' ' . $faculty->lname,
        'qceevaluator' => $evaluator ?? 'Faculty'
    ]);
    $disabled = $isDone ? 'disabled' : '';
    $cardClass = $isDone ? '' : 'card-hover';
    $nameInitials = collect(explode(' ', $faculty->fname))
        ->map(fn($name) => strtoupper(substr($name, 0, 1)))
        ->implode('');
    $middleInitial = substr($faculty->mname, 0, 1);
    $semesterText = $sy->semester == 1 ? '1st Sem' : ($sy->semester == 2 ? '2nd Sem' : ($sy->semester == 3 ? 'Summer' : $sy->semester));
    $designation = $faculty->designation ?? 'Faculty';
@endphp

<div class="col-lg-3 col-12">
    <a href="{{ $route }}" {{ $disabled }}>
        <div class="card h-100 {{ $cardClass }}">
            <div class="card-body p-4" @if($isDone) style="{{ $cardStyle }}" @endif>
                <div class="d-flex justify-content-between pb-5 mb-3">
                    <div>
                        <h3 class="fw-bold h5">{{ $faculty->lname }}, {{ $nameInitials }} {{ $middleInitial }}.</h3>
                        <span>{{ $faculty->rank ?? 'Part-time' }}</span><br>
                        <span style="font-size: 9pt;">
                            <span class="text-dark">{{ $sy->schlyear }}</span>, {{ $semesterText }}
                        </span>
                    </div>
                    <div>
                        <i class="ti ti-user fs-1 text-success"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center small">
                    <div class="text-muted">
                        <span class="text-dark">{{ $designation }}</span>
                    </div>
                    <div>
                        <span class="badge {{ $badgeClass }} textbold">
                            <i class="{{ $badgeIcon }}"></i> {{ $badgeText }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>