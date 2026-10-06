@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <style>
        /* Signature Canvas */
        .signature-canvas {
            position: relative;
            width: 100%;
            height: 300px;
            background-color: #fff;
            border: 2px dashed #28a745;
            border-radius: 8px;
            cursor: crosshair;
            transition: all 0.2s ease;
        }

        .signature-canvas:hover {
            border-color: #218838;
            background-color: #fcfffd;
        }

        /* Step Number */
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            margin-right: 10px;
            border-radius: 50%;
            background-color: #28a745;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
        }

        /* Checkbox */
        .custom-control-label {
            cursor: pointer;
        }

        .custom-control-input:checked ~ .custom-control-label {
            color: #28a745;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .signature-canvas {
                height: 240px;
            }
        }
    </style>
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services
                    </a>
                    <span class="text-muted">/ Faculty Evaluation</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Students Evaluation for Teachers
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    @if($setevalmode->statuseval === 'Off')
                                        <div class="col-12">
                                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                <div>
                                                    Faculty Evaluation is currently unavailable. Please check back later.
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($signature)
                                            @foreach($mysubj as $datafacsubprogen)
                                                @if($disabledsubj->contains('subjidrate', $datafacsubprogen->subjID))
                                                    <div class="col-lg-3 col-12">
                                                        <a href="#" disabled>
                                                            <div class="card h-100" style="background-color: rgba(230, 230, 230, 0.644)">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h4">{{ $datafacsubprogen->sub_name }}</h3>
                                                                            <span>{{ $datafacsubprogen->subSec }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-dark">{{ $datafacsubprogen->schlyear }}</span>, {{ $datafacsubprogen->semester == 1 ? '1st Sem' : ($datafacsubprogen->semester == 2 ? '2nd Sem' : ($datafacsubprogen->semester == 3 ? 'Summer' : $datafacsubprogen->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-book fs-1 text-secondary"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                @if(isset($datafacsubprogen->fname) && isset($datafacsubprogen->lname))
                                                                                    {{ substr($datafacsubprogen->fname, 0, 1) }}. {{ $datafacsubprogen->lname }}
                                                                                @else
                                                                                    No Instructor
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    @php

                                                        // ENCRYPT all values except qcefacname
                                                        $encryptedId = urlencode(Crypt::encryptString((string)$datafacsubprogen->subjID));
                                                        $encryptedFacID = urlencode(Crypt::encryptString((string)($datafacsubprogen->id)));
                                                    @endphp
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('show.evaluation.rate', ['id' => $encryptedId, 'qcefacID'  => $encryptedFacID, 'qcefacname'  => $datafacsubprogen->fname . ' ' . $datafacsubprogen->lname]) }}">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                                        <div>
                                                                            <h3 class="fw-bold h4">{{ $datafacsubprogen->sub_name }}</h3>
                                                                            <span>{{ $datafacsubprogen->subSec }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-success">{{ $datafacsubprogen->schlyear }}</span>, {{ $datafacsubprogen->semester == 1 ? '1st Sem' : ($datafacsubprogen->semester == 2 ? '2nd Sem' : ($datafacsubprogen->semester == 3 ? 'Summer' : $datafacsubprogen->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-book fs-1 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-dark">
                                                                                @if(isset($datafacsubprogen->fname) && isset($datafacsubprogen->lname))
                                                                                    {{ substr($datafacsubprogen->fname, 0, 1) }}. {{ $datafacsubprogen->lname }}
                                                                                @else
                                                                                    No Instructor
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="col-md-12">
                                                <!-- DATA PRIVACY NOTICE -->
                                                <div class="card mb-4">
                                                    <div class="card-body">
                                                        <div class="alert alert-light border mb-3">
                                                            <div class="d-flex">
                                                                <div class="mr-3 text-danger">
                                                                    <i class="fas fa-info-circle fa-lg"></i>
                                                                </div>
                                                                <div>
                                                                    <strong>Your personal information matters.</strong>
                                                                    <p class="mb-0 mt-1 text-muted">
                                                                        By signing this document, you acknowledge and consent to the collection,
                                                                        processing, and storage of your personal data for official purposes in
                                                                        accordance with applicable data privacy laws and institutional policies.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="mb-2 mb-md-0">
                                                                <span class="text-muted">
                                                                    <i class="fas fa-file-alt mr-1"></i>
                                                                    Please read the complete privacy compliance document.
                                                                </span>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                class="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-dataprivacy"
                                                            >
                                                                <i class="fas fa-file-pdf mr-1"></i>
                                                                View Data Privacy Compliance
                                                            </button>
                                                        </div>

                                                        <hr />

                                                        <!-- AGREEMENT -->
                                                        <div class="custom-control custom-checkbox">
                                                            <input
                                                                type="checkbox"
                                                                class="custom-control-input"
                                                                id="checkboxPrimaryAgree1"
                                                                name="studagree"
                                                                value="Yes I agree"
                                                            />
                                                            <label
                                                                class="custom-control-label font-weight-bold"
                                                                for="checkboxPrimaryAgree1"
                                                            >
                                                                I have read and understood the Data Privacy Notice
                                                            </label>
                                                            <small class="d-block text-muted ml-4 mt-1">
                                                                I agree to the collection, processing, and storage of my personal data for
                                                                the stated official purposes.
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- DIGITAL SIGNATURE -->
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <span
                                                                    class="d-flex align-items-center justify-content-center rounded-circle bg-white text-success"
                                                                    style="width: 42px; height: 42px;"
                                                                >
                                                                    <i class="fas fa-signature"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-1 font-weight-bold">Digital Signature</h5>
                                                                <small style="opacity: 0.9;">
                                                                    Draw your signature below to complete the process.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <form
                                                            class="form-horizontal"
                                                            method="POST"
                                                            action="{{ route('signature.upload') }}"
                                                            id="signatureform"
                                                        >
                                                            @csrf
                                                            <input
                                                                type="hidden"
                                                                id="studIDno"
                                                                name="studIDno"
                                                                value="{{ $studauth->stud_id }}"
                                                            />
                                                            <input
                                                                type="hidden"
                                                                id="camp"
                                                                name="camp"
                                                                value="{{ $studauth->campus }}"
                                                            />
                                                            <input
                                                                type="hidden"
                                                                id="signature"
                                                                name="signature"
                                                            />
                                                            <input
                                                                type="hidden"
                                                                name="signaturesubmit"
                                                                value="signature"
                                                            />

                                                            <!-- STEP 1 -->
                                                            <div class="signature-step mb-4">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <span class="step-number">1</span>
                                                                    <div>
                                                                        <h6 class="font-weight-bold mb-0">Sign inside the box</h6>
                                                                        <small class="text-muted">
                                                                            Use your mouse, trackpad, or touchscreen.
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div id="canvasDiv" class="signature-canvas">
                                                                    <!-- Signature canvas will be inserted here -->
                                                                </div>

                                                                <div class="mt-2">
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-info-circle mr-1"></i>
                                                                        Make sure your signature is clearly visible before saving.
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <!-- STEP 2 -->
                                                            <div class="signature-step">
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <span class="step-number">2</span>
                                                                    <div>
                                                                        <h6 class="font-weight-bold mb-0">Complete your signature</h6>
                                                                        <small class="text-muted">
                                                                            Clear your signature to try again or save when finished.
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between">
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-outline-danger mb-2"
                                                                        id="reset-btn"
                                                                    >
                                                                        <i class="fas fa-eraser mr-1"></i>
                                                                        Clear Signature
                                                                    </button>
                                                                    &nbsp;&nbsp;
                                                                    <button
                                                                        type="submit"
                                                                        name="signaturesubmit"
                                                                        id="btn-save"
                                                                        class="btn btn-success mb-2"
                                                                        disabled
                                                                    >
                                                                        <i class="fas fa-check mr-1"></i>
                                                                        Save & Submit Signature
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <!-- FOOTER NOTE -->
                                                    <div class="card-footer bg-light">
                                                        <small class="text-muted">
                                                            <i class="fas fa-lock mr-1 text-success"></i>
                                                            Your signature will be submitted securely for official processing.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-dataprivacy" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-file-type-pdf"></i> DATA PRIVACY COMPLIANCE
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <iframe src="{{ asset('uilibs/dataprivacy/DataPrivacyProvision.pdf') }}#toolbar=0" width="100%" height="500"></iframe>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Okay, I understand.</button>
                </div>
            </div>
        </div>
    </div>
@endsection
