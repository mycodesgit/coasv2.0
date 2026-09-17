@extends('layouts.master_yearbook')

@section('title')
CISS V.1.0 || YearBook
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Yearbook</li>
                            <li class="breadcrumb-item active mt-1">Inventory</li>
                        </ol>
                    </div>
                </div>
                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Yearbooks Management</h1>
                        <p class="text-muted small mb-0">View and manage yearbooks for all system activities.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add New
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" id="adYearbook">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                <input type="text" name="school_year" class="form-control form-control-sm" placeholder="e.g. 2025-2026" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Edition Title: <span class="text-danger">*</span></label>
                                                <input type="text" name="edition_title" class="form-control form-control-sm" placeholder="e.g. The Horizon Vol. 50">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Total Order: <span class="text-danger">*</span></label>
                                                <input type="text" name="total_ordered" class="form-control form-control-sm" placeholder="e.g. 1200">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Total Received: <span class="text-danger">*</span></label>
                                                <input type="text" name="total_received" class="form-control form-control-sm" value="0" placeholder="e.g. 1200">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Cost: <span class="text-danger">*</span></label>
                                                <input type="text" name="unit_cost" class="form-control form-control-sm" placeholder="0.00" onkeyup="formatNumber(this);">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                                <select name="campus" class="form-control form-control-sm">
                                                    <option value="MC">Main</option>
                                                    <option value="VC">Victorias</option>
                                                    <option value="SCC">San Carlos</option>
                                                    <option value="HC">Hinigaran</option>
                                                    <option value="MP">Moises Padilla</option>
                                                    <option value="IC">Ilog</option>
                                                    <option value="CA">Candoni</option>
                                                    <option value="CC">Cauayan</option>
                                                    <option value="SC">Sipalay</option>
                                                    <option value="HinC">Hinobaan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-outline-success">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> List
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3 p-2">
                                    <table id="yearbooklistTable" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>A.Y.</th>
                                                <th>Edition Title</th>
                                                <th>Total Ordered</th>
                                                <th>Total Received</th>
                                                <th>Cost</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editYearbookModal" tabindex="-1" role="dialog" aria-labelledby="editYearbookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editYearbookModalLabel">
                        <i class="fas fa-edit"></i> Edit Year Book
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editYearbookForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editYearbookModalId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="editYearbook" class="form-label fw-semibold">Year Book: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editYearbook" name="school_year" required>
                            </div>

                            <div class="col-md-12">
                                <label for="editEditableTitle" class="form-label fw-semibold">Edition Title: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editEditableTitle" name="edition_title" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editTotalOrdered" class="form-label fw-semibold">Total Ordered: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editTotalOrdered" name="total_ordered" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editTotalReceived" class="form-label fw-semibold">Total Received: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editTotalReceived" name="total_received" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editCost" class="form-label fw-semibold">Cost: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editCost" name="unit_cost" required>
                            </div>

                            <div class="col-md-6">
                                <label for="editIsCampus" class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                <select name="campus" id="editIsCampus" class="form-control form-control-sm" required>
                                    <option value="MC">Main</option>
                                    <option value="VC">Victorias</option>
                                    <option value="SCC">San Carlos</option>
                                    <option value="HC">Hinigaran</option>
                                    <option value="MP">Moises Padilla</option>
                                    <option value="IC">Ilog</option>
                                    <option value="CA">Candoni</option>
                                    <option value="CC">Cauayan</option>
                                    <option value="SC">Sipalay</option>
                                    <option value="HinC">Hinobaan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var yearBookReadRoute = "{{ route('yerbokshipment.show') }}";
        var yearBookCreateRoute = "{{ route('yerbokshipment.create') }}";
        var yearBookUpdateRoute = "{{ route('yerbokshipment.update', ['id' => ':id']) }}";

        function formatNumber(input) {
            const value = input.value.replace(/[^\d.]/g, '');
            const formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            input.value = formattedValue;
        }
    </script>
@endsection
