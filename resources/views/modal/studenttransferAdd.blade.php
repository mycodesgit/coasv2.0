<div class="modal fade mt-6" id="modal-studenttrans">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Transfer Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form class="form-horizontal" action="{{ route('studtransferCreate') }}" method="post" id="adStudTransfer">  
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Student ID No.: <span class="text-danger">*</span></label>
                                <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Name:</label>
                                <input type="text" id="studentName" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="primaryId" name="studbaseprim_id" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>From Campus:</label>
                                <input type="text" id="fromCampus" name="fromcampus" oninput="this.value = this.value.toUpperCase()"  class="form-control form-control-sm" readonly="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>To Campus:</label>
                                <select class="form-control form-control-sm" name="tocampus">
                                    @if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Arlos' || Auth::guard('web')->user()->lname == 'Gallardo' || Auth::guard('web')->user()->fname == 'Regielyn' || Auth::guard('web')->user()->lname == 'Baldevino')
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
                                    @else
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-success">Transfer Student Now</button>
                </div>
            </form>
        </div>
    </div>
</div>