<div class="modal fade mt-6" id="modal-kioskuser">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form class="form-horizontal" action="{{ route('adminkioskCreate') }}" method="post" id="adKioskuser">  
                @csrf

                <div class="modal-body">
                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Student ID No.: <span class="text-danger">*</span></label>
                                <input type="text" name="studid" class="form-control" oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Name: <span class="text-danger">*</span></label>
                                <input type="text" id="studentName" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Password: <span class="text-danger">*</span></label>
                                <input type="text" name="password" id="passwordInput" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Password" class="form-control" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer justify-content-between">
                    <button type="button" id="generatePassword" class="btn btn-outline-warning">
                        <i class="fas fa-key"></i> Generate Pass
                    </button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>