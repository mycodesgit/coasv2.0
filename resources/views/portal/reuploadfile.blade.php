@extends('layouts.master_apply')

@section('title')
CISS V.1.0 || Apply Admission
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}

                @if ($admissionmode->statusadmission == 'On')
                    <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                        <div class="card-body">
                            <ol class="breadcrumb" style="margin-bottom: -3px;">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admission-portal') }}" class="btn btn-success btn-sm text-light">
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active mt-1">Apply Admission - Re-upload Documents</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row g-3 mb-3 mt-1">
                        @php
                            date_default_timezone_set('Asia/Manila');

                            $now = now();
                            $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                            $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                        @endphp

                        <div class="col-md-12">
                            @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                                <img src="{{ asset('uilibs/images/limit-has-been-reached.jpg') }}" width="100%" class="img-responsive">
                            @else
                                <form id="fileUploadForm" method="post" action="{{ route('uploadDocuments') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="card card-animate">
                                        <div class="card-header pt-3">
                                            <h6 class="card-title">
                                                <i class="ti ti-search"></i> Search to show data
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="search_lastname" class="form-label fw-semibold">Last Name: <span class="text-danger">*</span></label>
                                                    <input type="text" id="search_lastname" class="form-control form-control-sm" name="lname" placeholder="Enter Last Name" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="search_firstname" class="form-label fw-semibold">First Name: <span class="text-danger">*</span></label>
                                                    <input type="text" id="search_firstname" class="form-control form-control-sm" name="fname" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Preferred Campus: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="campus" id="campus">
                                                        <option disabled selected>Select</option>
                                                        <option value="MC">Main</option>
                                                        <option value="VC">Victorias</option>
                                                        <option value="SCC">San Carlos</option>
                                                        <option value="MP">Moises Padilla</option>
                                                        <option value="HC">Hinigaran</option>
                                                        <option value="IC">Ilog</option>
                                                        <option value="CA">Candoni</option>
                                                        <option value="CC">Cauayan</option>
                                                        <option value="SC">Sipalay</option>
                                                        <option value="HinC">Hinobaan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" id="searchApplicant" class="btn btn-success mt-3">Search</button>
                                        </div>
                                    </div>

                                    <!-- Auto-filled Applicant Information -->
                                    <div id="card-1" style="display: none;">
                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <label for="admissionid">Your Admission ID</label>
                                                <input type="text" id="admissionid" name="admissionid" class="form-control" readonly>
                                                <input type="hidden" id="fname" name="fname" class="form-control" readonly>
                                                <input type="hidden" id="lname" name="lname" class="form-control" readonly>
                                                <input type="hidden" id="primaryid" name="id" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <p>
                                            @if(Session::has('success'))
                                                <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
                                            @elseif (Session::has('fail'))
                                                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                            @endif
                                        </p>


                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title m-0">Please re-upload the missing documents listed below</h5>
                                            </div>
                                        </div>

                                        <div class="card" id="validid">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="col-md-12">
                                                            <label>Upload School ID <span class="text-danger">*</span></label>
                                                            <input type="file" name="studiddoc_image" class="form-control form-control-sm" id="fileInput" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="appreq">
                                            <div class="card-body">
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Application Requirement <span style="font-size: 12pt;color:#ff0000;"></span></h4>
                                                </div>

                                                <!-- Selection -->
                                                <div class="form-group">
                                                    <label for="applicantType">Select Applicant Type <span class="text-danger">*</span></label>
                                                    <select id="applicantType" class="form-control form-control-sm">
                                                        <option value="">-- Select an option --</option>
                                                        <option value="grade12">Grade 12 Students (Graduating)</option>
                                                        <option value="shsGrad">Senior High School Graduates</option>
                                                        <option value="apptransferee">Transferees</option>
                                                        <option value="als">ALS Graduates/Passers</option>
                                                        <option value="lifelong">Life-long Learner</option>
                                                    </select>
                                                </div>

                                                <!-- Upload Sections -->
                                                <div class="form-group upload-section" id="grade12" style="">
                                                    <label>Upload Grade 12 Requirement <span class="text-danger">*</span></label>
                                                    <input type="file" name="grade12File" class="form-control form-control-sm file-input" accept="image/*">
                                                </div>

                                                <div class="form-group upload-section" id="shsGrad" style="display:none;">
                                                    <label>Upload SHS Graduate Requirement <span class="text-danger">*</span></label>
                                                    <input type="file" name="shsFile" class="form-control form-control-sm file-input" accept="image/*">
                                                </div>

                                                <div class="form-group upload-section" id="apptransferee" style="display:none;">
                                                    <label>Upload Transferee Requirement <span class="text-danger">*</span></label>
                                                    <input type="file" name="transfereeFile" class="form-control form-control-sm file-input" accept="image/*">
                                                </div>

                                                <div class="form-group upload-section" id="als" style="display:none;">
                                                    <label>Upload ALS Requirement <span class="text-danger">*</span></label>
                                                    <input type="file" name="alsFile" class="form-control form-control-sm file-input" accept="image/*">
                                                </div>

                                                <div class="form-group upload-section" id="lifelong" style="display:none;">
                                                    <label>Upload Life-long Learner Requirement <span class="text-danger">*</span></label>
                                                    <input type="file" name="lifelongFile" class="form-control form-control-sm file-input" accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="proofdoc">
                                            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="ti ti-file-certificate fs-4 text-success mt-1"></i>
                                                    <div>
                                                        <h5 class="card-title mb-1">
                                                            Proof of Equity Target Documents
                                                            <span class="text-danger">*</span>
                                                        </h5>
                                                        <p class="text-muted small mb-0">
                                                            Select <strong>one (1)</strong> category that best describes your parent or legal guardian,
                                                            then upload the matching document below.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                {{-- Live status of the current selection --}}
                                                <div class="proof-status d-flex align-items-center gap-2 rounded-3 px-3 py-2 mb-3" id="proofSelectionStatus" role="status" aria-live="polite">
                                                    <i class="ti ti-info-circle" id="proofSelectionIcon"></i>
                                                    <span class="mb-0" id="proofSelectionText">No category selected yet. Please choose one (1) option below.</span>
                                                </div>

                                                {{-- Filter helper to quickly find the right category --}}
                                                <div class="mb-3">
                                                    <label for="proofFilter" class="form-label fw-semibold mb-1">
                                                        <i class="ti ti-search me-1"></i> Find your category
                                                    </label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="search" id="proofFilter" class="form-control" placeholder="Type a keyword (e.g. 4Ps, Solo Parent, PWD)">
                                                        <button class="btn btn-outline-secondary" type="button" id="proofFilterClear" title="Clear filter">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="form-text" id="proofFilterCount">Showing all 14 categories.</div>
                                                </div>

                                                {{-- Category options --}}
                                                <div class="proof-option-list d-flex flex-column gap-2" id="proofOptionList" role="radiogroup" aria-label="Proof of equity target document categories">

                                                    {{-- 1. 4Ps Members --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="4ps 4p member members mswd dswd household beneficiary poor">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary1" name="typefileproofupload" value="4Ps">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">4Ps Members</span>
                                                            <span class="proof-option-desc">Upload a 4Ps Certification from MSWD/DSWD showing the household number and beneficiary's name, or a 4Ps Household ID.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 2. Indigenous People --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="ip ips indigenous people person municipal city indigenous office certificate tribe">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary2" name="typefileproofupload" value="IPs">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Indigenous People (IPs)</span>
                                                            <span class="proof-option-desc">Upload an original IP Certificate from the Municipal or City Indigenous Office with an original signature, together with a valid ID.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 3. Resident of Armed Conflict Areas --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="raca resident armed conflict areas dilg conflict">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary3" name="typefileproofupload" value="RACA">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Resident of Armed Conflict Areas</span>
                                                            <span class="proof-option-desc">Upload a Certification from DILG with your name.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 4. Parents Who Are Business Owners --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="pwabo business owner owners bir 1701 entrepreneur business">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary4" name="typefileproofupload" value="PWABO">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Parents Who Are Business Owners</span>
                                                            <span class="proof-option-desc">Upload BIR Form 1701A-2021.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 5. Parents Employed in Government / Private Agencies --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="peigpa government private agency agencies employed employee salary compensation 2316 human resource">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary5" name="typefileproofupload" value="PEIGPA">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Parents Employed in Government or Private Agencies</span>
                                                            <span class="proof-option-desc">Upload BIR Form 2316-2021 or a Certification from the Human Resource Office showing salary and compensation details.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 6. Parents Working Abroad --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="pwa working abroad overseas ofw employment contract foreign">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary6" name="typefileproofupload" value="PWA">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Parents Working Abroad</span>
                                                            <span class="proof-option-desc">Upload an Overseas Employment Contract (OEC).</span>
                                                        </span>
                                                    </label>

                                                    {{-- 7. Applicant with Deceased Parents --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="low income deceased parents died barangay certification orphan">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary7" name="typefileproofupload" value="Low Income">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Applicant with Deceased Parents</span>
                                                            <span class="proof-option-desc">Upload a Barangay Certification confirming that both parents are deceased.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 8. Solo / Single Parents --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="solo parent parents single separated widower widow sworn statement">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary8" name="typefileproofupload" value="Solo Parent">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Solo / Single Parents</span>
                                                            <span class="proof-option-desc">Upload a Solo Parent ID/Certification from MSWD/DSWD or a Notarized Sworn Statement indicating current status (Single, Solo parent, Separated, Widow/er).</span>
                                                        </span>
                                                    </label>

                                                    {{-- 9. Person with Disabilities --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="pwd pwds person with disability disabilities pwdid certificate dswd family">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary9" name="typefileproofupload" value="PWDs">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Person with Disabilities (PWDs)</span>
                                                            <span class="proof-option-desc">
                                                                <ul class="mb-0 ps-3">
                                                                    <li>For applicants: upload a PWD ID.</li>
                                                                    <li>For family members: upload a PWD Certificate from DSWD stating the relationship with the applicant.</li>
                                                                </ul>
                                                            </span>
                                                        </span>
                                                    </label>

                                                    {{-- 10. Children of Rice/Corn Farmers --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="corcf rice corn farmer farmers agrarian reform agriculture da dar cao membership cooperative">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary10" name="typefileproofupload" value="CORCF">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Children of Rice/Corn Farmers</span>
                                                            <span class="proof-option-desc">
                                                                <ul class="mb-0 ps-3">
                                                                    <li>Certificate of Membership from the local Department of Agriculture listing the parent/guardian's name.</li>
                                                                    <li>Certification issued by an Association or Cooperative President.</li>
                                                                    <li>Certification from DA/DAR/CAO confirming status as an Agrarian Reform Beneficiary.</li>
                                                                </ul>
                                                            </span>
                                                        </span>
                                                    </label>

                                                    {{-- 11. Children of Coconut Farmers --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="cocf coconut farmer farmers association membership">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary11" name="typefileproofupload" value="COCF">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Children of Coconut Farmers</span>
                                                            <span class="proof-option-desc">Upload a Certificate of Membership from the local coconut association listing the parent/guardian's name.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 12. Children of Fisherfolk --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="cof fisherfolk fishing fisher fisherman association active">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary12" name="typefileproofupload" value="COF">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Children of Fisherfolk</span>
                                                            <span class="proof-option-desc">
                                                                <ul class="mb-0 ps-3">
                                                                    <li>Certificate of Membership from a local fisherfolk association, or a certification issued by the Association President.</li>
                                                                    <li>Note: the association must be active.</li>
                                                                </ul>
                                                            </span>
                                                        </span>
                                                    </label>

                                                    {{-- 13. Children of Tricycle / Jeepney Drivers --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="cotjdo tricycle jeepney driver driver-operator operator operators toda">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary13" name="typefileproofupload" value="COTJDO">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Children of Tricycle / Jeepney Drivers or Operators</span>
                                                            <span class="proof-option-desc">Upload a Certificate of Membership from a local TODA listing the parent/guardian's name.</span>
                                                        </span>
                                                    </label>

                                                    {{-- 14. Unclassified --}}
                                                    <label class="custom-option-card proof-option-card d-flex align-items-start p-3 rounded-3 border" data-search="uhnfis unclassified unemployed housewife househusband no fixed income self supporting self-supporting">
                                                        <input type="radio" class="form-check-input me-3 mt-1" id="radioPrimary14" name="typefileproofupload" value="UHNFIS">
                                                        <span class="flex-grow-1">
                                                            <span class="proof-option-title d-block mb-1">Unclassified (Unemployed, No Fixed Income, Self-Supporting)</span>
                                                            <span class="proof-option-desc">Upload a Notarized Sworn Statement indicating the parents'/guardian's name, source of income, and income amount.</span>
                                                        </span>
                                                    </label>

                                                </div>

                                                {{-- Empty state when the filter matches nothing --}}
                                                <div class="text-center text-muted py-4 d-none" id="proofNoMatch">
                                                    <i class="ti ti-search-off fs-2 d-block mb-2"></i>
                                                    <p class="mb-1 fw-semibold">No matching category</p>
                                                    <p class="small mb-0">Try another keyword or clear the filter to see all categories.</p>
                                                </div>

                                                {{-- Upload section --}}
                                                <div class="mt-4 pt-3 border-top">
                                                    <label for="proofdoc_image" class="form-label fw-semibold" id="uploadLabel">
                                                        Upload Proof of Equity Target Document <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="file" id="proofdoc_image" name="proofdoc_image" class="form-control" accept="image/*">
                                                    <div class="form-text" id="proofdocFileHint">Accepted formats: JPG, PNG (Maximum file size: 5MB)</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>

                                    <br><br>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        {{-- Top decorative accent bar (Danger/Warning red) --}}
                        <div class="bg-danger" style="height: 6px;"></div>
                        <div class="card-body p-4 p-md-5 text-center">
                            {{-- Status Icon --}}
                            <div class="mb-4">
                                <span class="fa-stack fa-2x">
                                    <i class="fas fa-circle fa-stack-2x text-danger opacity-25"></i>
                                    <i class="fas fa-lock fa-stack-1x text-danger"></i>
                                </span>
                            </div>

                            {{-- Heading & Messages --}}
                            <h2 class="text-danger fw-bold mb-3">Admission Portal is Closed</h2>
                            <h4 class="fw-semibold text-dark mb-3">We are not accepting applications at this time.</h4>
                            <p class="text-muted mb-4 fs-6">
                                The application period for this term has ended or has not yet opened. Please check back later or monitor official announcements for upcoming application schedules.
                            </p>

                            {{-- Info Box (Optional: Display upcoming dates or help info) --}}
                            <div class="bg-light border rounded-3 p-3 mb-4 text-start max-width-500 mx-auto">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                                    <div>
                                        <strong class="d-block text-dark">Need Assistance?</strong>
                                        <span class="text-muted small">
                                            If you believe this is an error or have questions regarding your application, please contact the Admission Office.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admission-portal') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Portal Home
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Option Card Styling */
    .custom-option-card {
        background-color: #f8f9fa;
        border-color: #e9ecef !important;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .custom-option-card:hover {
        background-color: #ffffff;
        border-color: #00c951 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Highlight card when radio is checked */
    .custom-option-card:has(input[type="radio"]:checked) {
        background-color: #f0f7ff;
        border-color: #00c951 !important;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);
    }

    .custom-option-card input[type="radio"]:checked {
        background-color: #00c951;
        border-color: #00c951;
    }

    /* ===== Proof of Equity Target Documents (#proofdoc) ===== */

    /* Selection status banner */
    .proof-status {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #6c757d;
        font-size: .875rem;
        transition: all .2s ease-in-out;
    }

    .proof-status.is-selected {
        background-color: #e9f7ef;
        border-color: #00c951;
        color: #0a3622;
    }

    /* Scrollable list of categories */
    .proof-option-list {
        max-height: 30rem;
        overflow-y: auto;
        padding-right: .35rem;
    }

    .proof-option-list::-webkit-scrollbar {
        width: 6px;
    }

    .proof-option-list::-webkit-scrollbar-thumb {
        background-color: #ced4da;
        border-radius: 10px;
    }

    /* Category option cards */
    .proof-option-card .form-check-input {
        float: none;
        margin-left: 0;
        flex: 0 0 auto;
        cursor: pointer;
    }

    .proof-option-title {
        font-weight: 600;
        color: #1f2937;
    }

    .proof-option-desc {
        display: block;
        color: #6c757d;
        font-size: .875rem;
        line-height: 1.45;
    }

    .proof-option-desc ul {
        padding-left: 1.1rem;
    }

    .proof-option-card:has(input[type="radio"]:checked) {
        background-color: #f0f7ff;
        border-color: #00c951 !important;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);
    }

    .proof-option-card:has(input[type="radio"]:checked) .proof-option-title {
        color: #0a3622;
    }

    @media (max-width: 767.98px) {
        .proof-option-list {
            max-height: none;
            overflow-y: visible;
            padding-right: 0;
        }
    }
    </style>
@endpush

@push('scripts')
    <script>
        let historyLock = setInterval(() => {
            history.pushState(null, null, location.href);
        }, 100);

        document.getElementById('searchApplicant').addEventListener('click', function () {
            let lastname = document.getElementById('search_lastname').value.trim();
            let firstname = document.getElementById('search_firstname').value.trim();
            let campus = document.getElementById('campus').value;

            if (!lastname || !firstname || campus === 'Select') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Fields',
                    text: 'Please fill all search fields.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            fetch('{{ route("searchApplicant") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ lastname, firstname, campus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('admissionid').value = data.applicant.admission_id;
                    document.getElementById('primaryid').value = data.applicant.primaryid;
                    document.getElementById('lname').value = data.applicant.lname;
                    document.getElementById('fname').value = data.applicant.fname;

                    document.getElementById('card-1').style.display = 'block';

                    document.getElementById('validid').style.display = 'none';
                    document.getElementById('appreq').style.display = 'none';
                    document.getElementById('proofdoc').style.display = 'none';

                    let allowed = [];
                    try {
                        allowed = JSON.parse(data.applicant.reuploadallow);
                    } catch (e) {
                        console.warn('Invalid reuploadallow format:', e);
                    }

                    if (Array.isArray(allowed)) {
                        allowed.forEach(id => {
                            let card = document.getElementById(id);
                            if (card) {
                                card.style.display = 'block';
                            }
                        });
                    }

                    syncDocumentValidations();

                    Swal.fire({
                        icon: 'success',
                        title: 'Applicant Found',
                        text: 'The applicant data has been retrieved successfully.',
                        confirmButtonText: 'OK'
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: 'No applicant found with the provided details. Only Applicant needed to re-upload documents will be show',
                        confirmButtonText: 'OK'
                    });

                    document.getElementById('card-1').style.display = 'none';
                    document.getElementById('validid').style.display = 'none';
                    document.getElementById('appreq').style.display = 'none';
                    document.getElementById('proofdoc').style.display = 'none';

                    syncDocumentValidations();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while searching. Please try again later.',
                    confirmButtonText: 'OK'
                });
            });
        });

        $(document).on('submit', '#fileUploadForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('uploadDocuments') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Updated files:', response.data);

                        let countdown = 5;
                        Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            html: `Your documents have been uploaded successfully.<br><b>Redirecting in <span id="countdown">${countdown}</span> seconds...</b>`,
                            timer: countdown * 1000,
                            showConfirmButton: false,
                            didOpen: () => {
                                const countdownElement = Swal.getHtmlContainer().querySelector('#countdown');
                                const interval = setInterval(() => {
                                    countdown--;
                                    countdownElement.textContent = countdown;
                                    if (countdown <= 0) {
                                        clearInterval(interval);
                                    }
                                }, 1000);
                            },
                            willClose: () => {
                                window.location.href = "{{ route('repupredirectexpire') }}";
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error || 'An error occurred. Please try again.'
                    });
                }
            });
        });
    </script>

    <script>
        /* =========================================================
           Proof of Equity Target Documents (#proofdoc)
           ========================================================= */
        const proofCard = document.getElementById('proofdoc');
        const proofOptionCards = Array.from(document.querySelectorAll('#proofdoc .proof-option-card'));
        const proofRadios = Array.from(document.querySelectorAll('#proofdoc input[name="typefileproofupload"]'));
        const proofFileInput = document.getElementById('proofdoc_image');
        const proofFilterInput = document.getElementById('proofFilter');
        const proofFilterClear = document.getElementById('proofFilterClear');
        const proofFilterCount = document.getElementById('proofFilterCount');
        const proofNoMatch = document.getElementById('proofNoMatch');
        const proofStatusBox = document.getElementById('proofSelectionStatus');
        const proofStatusIcon = document.getElementById('proofSelectionIcon');
        const proofStatusText = document.getElementById('proofSelectionText');
        const proofUploadLabel = document.getElementById('uploadLabel');
        const proofFileHint = document.getElementById('proofdocFileHint');
        const PROOF_MAX_FILE_SIZE = 5 * 1024 * 1024;

        /* An element is only "visible" when it and all of its ancestors are rendered */
        function isElementVisible(element) {
            if (!element) return false;
            if (getComputedStyle(element).display === 'none') return false;
            return element.offsetParent !== null;
        }

        /* Only the cards that are actually shown may hold "required" inputs,
           otherwise the browser blocks the submit because the control cannot be focused. */
        function syncProofDocValidation() {
            const visible = isElementVisible(proofCard);
            const selected = proofRadios.find(radio => radio.checked);

            proofRadios.forEach(radio => {
                radio.required = visible;
                radio.setCustomValidity('');
            });

            if (proofFileInput) {
                proofFileInput.required = visible;
            }

            if (visible && !selected && proofRadios.length) {
                proofRadios[0].setCustomValidity('Please select one (1) category that applies to your parent or legal guardian.');
            }
        }

        /* Keep the status banner and the upload label in sync with the chosen category */
        function refreshProofSelection() {
            const selected = proofRadios.find(radio => radio.checked);

            if (!selected) {
                proofStatusBox.classList.remove('is-selected');
                proofStatusIcon.className = 'ti ti-info-circle';
                proofStatusText.textContent = 'No category selected yet. Please choose one (1) option below.';
                proofUploadLabel.innerHTML = 'Upload Proof of Equity Target Document <span class="text-danger">*</span>';
            } else {
                const card = selected.closest('.proof-option-card');
                const title = card ? card.querySelector('.proof-option-title').textContent.trim() : '';

                proofStatusBox.classList.add('is-selected');
                proofStatusIcon.className = 'ti ti-circle-check';
                proofStatusText.innerHTML = '<strong>Selected:</strong> ' + title;
                proofUploadLabel.innerHTML = 'Upload Document &ndash; <strong class="text-dark">' + title + '</strong> <span class="text-danger">*</span>';
            }

            syncProofDocValidation();
        }

        /* Live filter so the applicant can quickly locate the right category */
        function applyProofFilter() {
            const term = (proofFilterInput.value || '').trim().toLowerCase();
            let matches = 0;

            proofOptionCards.forEach(card => {
                const haystack = ((card.dataset.search || '') + ' ' + card.textContent).toLowerCase();
                const visible = term === '' || haystack.indexOf(term) !== -1;

                card.style.display = visible ? '' : 'none';
                if (visible) matches++;
            });

            proofNoMatch.classList.toggle('d-none', matches !== 0);

            proofFilterCount.textContent = term === ''
                ? 'Showing all ' + proofOptionCards.length + ' categories.'
                : 'Showing ' + matches + ' of ' + proofOptionCards.length + ' categories matching "' + proofFilterInput.value.trim() + '".';
        }

        proofRadios.forEach(radio => {
            radio.addEventListener('change', refreshProofSelection);
        });

        if (proofFilterInput) {
            proofFilterInput.addEventListener('input', applyProofFilter);
        }

        if (proofFilterClear) {
            proofFilterClear.addEventListener('click', function () {
                proofFilterInput.value = '';
                applyProofFilter();
                proofFilterInput.focus();
            });
        }

        if (proofFileInput) {
            proofFileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) {
                    proofFileHint.textContent = 'Accepted formats: JPG, PNG (Maximum file size: 5MB)';
                    proofFileHint.classList.remove('text-danger');
                    return;
                }

                if (file.size > PROOF_MAX_FILE_SIZE) {
                    proofFileHint.textContent = 'File is too large (' + (file.size / 1048576).toFixed(2) + 'MB). Maximum allowed size is 5MB.';
                    proofFileHint.classList.add('text-danger');
                    this.value = '';
                    return;
                }

                proofFileHint.textContent = 'Ready to upload: ' + file.name;
                proofFileHint.classList.remove('text-danger');
            });
        }

        refreshProofSelection();
    </script>

    <script>
        const applicantType = document.getElementById('applicantType');
        const sections = document.querySelectorAll('.upload-section');

        /* Syncs the "required" state of every document field with the cards
           that are currently visible on the form. */
        function syncDocumentValidations() {
            const appReqVisible = isElementVisible(document.getElementById('appreq'));

            sections.forEach(section => {
                const input = section.querySelector('.file-input');
                if (!input) return;

                const isActive = appReqVisible && section.id === applicantType.value;

                input.required = isActive;

                if (!isActive) {
                    input.value = '';
                    section.style.display = 'none';
                }
            });

            syncProofDocValidation();
        }

        applicantType.addEventListener('change', function() {
            sections.forEach(section => {
                const input = section.querySelector('.file-input');

                // Always clear the file input when switching
                input.value = "";

                if (section.id === this.value) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });

            syncDocumentValidations();
        });

        window.syncDocumentValidations = syncDocumentValidations;

        syncDocumentValidations();
    </script>
@endpush
