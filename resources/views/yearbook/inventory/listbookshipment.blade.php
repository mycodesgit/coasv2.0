@extends('layouts.master_yearbook')

@section('title')
CISS V.1.0 || YearBook Shipments
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <div class="card mb-3" style="background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Yearbook</li>
                            <li class="breadcrumb-item active mt-1">Shipment & Intake</li>
                        </ol>
                    </div>
                </div>

                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Shipment Management</h1>
                        <p class="text-muted small mb-0">Track supplier shipments and process incoming office inventory.</p>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Add Shipment Form -->
                    <div class="col-md-4">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-truck-delivery"></i> New Supplier Shipment
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" id="addShipmentForm">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Select Yearbook Batch: <span class="text-danger">*</span></label>
                                                <select name="yearbook_id" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>-- Choose Yearbook --</option>
                                                    @foreach($yearbooks as $yb)
                                                        <option value="{{ $yb->id }}">{{ $yb->edition_title }} (SY {{ $yb->school_year }})</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Supplier Name: <span class="text-danger">*</span></label>
                                                <input type="text" name="supplier_name" class="form-control form-control-sm" placeholder="e.g. Acme Publishing Press" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Tracking / Waybill #:</label>
                                                <input type="text" name="tracking_number" class="form-control form-control-sm" placeholder="e.g. TRK-990123">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Quantity Sent/Dispatched: <span class="text-danger">*</span></label>
                                                <input type="number" name="quantity_sent" class="form-control form-control-sm" placeholder="e.g. 500" min="1" required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Notes / Remarks:</label>
                                                <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Optional shipment notes..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-paper-plane"></i> Log Dispatch
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Shipments DataTable -->
                    <div class="col-md-8">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> Shipment Logs
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3 p-2">
                                    <table id="shipmentListTable" class="table table-hover table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Batch</th>
                                                <th>Supplier</th>
                                                <th>Tracking #</th>
                                                <th>Qty Sent</th>
                                                <th>Qty Recv</th>
                                                <th>Status</th>
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

    <!-- Modal for Receiving Stock into Inventory -->
    <div class="modal fade" id="receiveShipmentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        <i class="fas fa-box-open"></i> Receive Stock into Office
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="receiveShipmentForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="receiveShipmentId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert alert-info py-2 mb-0">
                                    <small id="modalShipmentInfo">Confirming intake will directly update the inventory count.</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Quantity Actually Received: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="receiveQuantity" name="quantity_received" min="0" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Intake Remarks / Discrepancy Notes:</label>
                                <textarea name="notes" id="receiveNotes" class="form-control form-control-sm" rows="3" placeholder="Note any damages or missing copies..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Process Stock Intake</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var shipmentReadRoute = "{{ route('shipment.show') }}";
        var shipmentCreateRoute = "{{ route('shipment.create') }}";
        var shipmentReceiveRoute = "{{ route('shipment.receive') }}";
    </script>
@endsection
