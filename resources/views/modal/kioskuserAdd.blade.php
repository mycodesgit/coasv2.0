<div class="modal fade" id="modal-kioskuser">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form class="form-horizontal" action="{{ route('adminkioskCreate') }}" method="post" id="adKioskuser">  
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Student ID No.:</span></label>
                                <input type="text" name="studid" class="form-control" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Password:</span></label>
                                <input type="text" name="password" id="passwordInput" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Password" class="form-control" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer justify-content-between">
                    <button type="button" id="generatePassword" class="btn btn-success">
                        <i class="fas fa-key"></i> Generate Pass
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>