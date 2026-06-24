@php
    use App\Helpers\EncryptionHelper;

    $isDone = $disabledsubj->contains($faculty->facID);
    $cardStyle = $isDone ? 'background-color: rgba(230, 230, 230, 0.644)' : '';
    $badgeClass = $isDone ? 'bg-success' : 'bg-info';
    $badgeIcon = $isDone ? 'ti ti-check' : 'ti ti-x';
    $badgeText = $isDone ? 'Done Evaluate' : 'Not Done';
    
    // Get the first subjID if multiple exist
    $subjID = isset($faculty->subjIDs) && !empty($faculty->subjIDs) 
        ? $faculty->subjIDs[0] 
        : ($faculty->subjID ?? $faculty->id);
    
    // ENCRYPT all values except qcefacname
    $encryptedId = EncryptionHelper::encryptUrl($subjID);
    $encryptedFacID = EncryptionHelper::encryptUrl($faculty->facID ?? $faculty->id);
    $encryptedEvaluator = isset($evaluator) ? EncryptionHelper::encryptUrl($evaluator) : EncryptionHelper::encryptUrl('Faculty');
    // qcefacname is NOT encrypted - kept as plain text for readability

    $route = $isDone ? '#' : route('supfacevalrate', [
        'id' => $encryptedId,
        'qcefacID' => $encryptedFacID,
        'qcefacname' => $faculty->fname . ' ' . $faculty->lname,
        'qceevaluator' => $encryptedEvaluator   
    ]);
    $disabled = $isDone ? 'disabled' : '';
    $cardClass = $isDone ? '' : 'card-hover';
    $nameInitials = collect(explode(' ', $faculty->fname))
        ->map(fn($name) => strtoupper(substr($name, 0, 1)))
        ->implode('');
    $middleInitial = substr($faculty->mname, 0, 1);
    $semesterText = $currsem->first()->semester == 1 ? '1st Sem' : ($currsem->first()->semester == 2 ? '2nd Sem' : ($currsem->first()->semester == 3 ? 'Summer' : $currsem->first()->semester));
    
    // Handle multiple designations
    $designations = isset($faculty->designations) && is_array($faculty->designations) 
        ? $faculty->designations 
        : [$faculty->designation ?? 'Faculty'];
    
    $primaryDesignation = $designations[0] ?? 'Faculty';
    $hasMultipleDesignations = count($designations) > 1;
    
    // Color mapping for designations
    $colorMap = [
        'Dean' => 'danger',
        'Program Head' => 'primary',
        'Division Chair' => 'warning',
        'CampusAdmin' => 'success',
        'Faculty' => 'secondary'
    ];
    
    $designationBadges = collect($designations)->map(function($desig) use ($colorMap) {
        $color = $colorMap[$desig] ?? 'secondary';
        return "<span class='badge bg-{$color} me-1'>{$desig}</span>";
    })->implode(' ');
    
    // Get college info
    $facColleges = isset($faculty->facColleges) && is_array($faculty->facColleges) 
        ? $faculty->facColleges 
        : [$faculty->facCollege ?? $faculty->faccollege ?? 'N/A'];
    
    $collegeBadges = collect($facColleges)->map(function($college) {
        return "<span class='badge bg-secondary me-1'>{$college}</span>";
    })->implode(' ');
@endphp

<div class="col-lg-3 col-12">
    <a href="{{ $route }}" {{ $disabled }}>
        <div class="card h-100 {{ $cardClass }}">
            <div class="card-body p-4" @if($isDone) style="{{ $cardStyle }}" @endif>
                <div class="d-flex justify-content-between pb-3 mb-3">
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
                        <span class="text-dark">
                            {{ $primaryDesignation }}
                            @if($hasMultipleDesignations)
                                <span class="badge bg-info rounded-pill" title="Has multiple roles">
                                    <i class="ti ti-info-circle"></i> {{ count($designations) }}
                                </span>
                            @endif
                        </span>
                        @if($hasMultipleDesignations)
                            <br>
                            <div class="mt-1">
                                {!! $designationBadges !!}
                            </div>
                        @endif
                        @if(count($facColleges) > 0)
                            <br>
                            <div class="mt-1">
                                <small>College: {!! $collegeBadges !!}</small>
                            </div>
                        @endif
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