<div class="modal fade mt-6" id="modal-chedsch">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add Scholarship
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-horizontal" action="{{ route('chedscholarCreate') }}" method="post" id="addChedSch">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">CHED Scholarship: <span class="text-danger">*</span></label>
                                <input type="text" name="chedsch_name" oninput="this.value = this.value.toUpperCase()" placeholder="Enter CHED Scholarship" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                    <button type="button" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
