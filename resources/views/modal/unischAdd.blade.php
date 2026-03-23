<div class="modal fade mt-6" id="modal-unisch">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add Scholarship
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
               <form class="form-horizontal" action="{{ route('unischolarCreate') }}" method="post" id="addUniSch">  
                    @csrf

                    <div class="form-group">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>CPSU Scholarship: <span class="text-danger">*</span></label>
                                <input type="text" name="unisch_name" oninput="this.value = this.value.toUpperCase()" placeholder="Enter CHED Scholarship" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>   
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>