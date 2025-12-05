<div class="modal fade" id="modal-studenttrans">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Transfer Student
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form class="form-horizontal" action="{{ route('studtransferCreate') }}" method="post" id="adStudTransfer">  
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Student ID No.:</span></label>
                                <input type="text" name="stud_id" class="form-control" oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Name:</span></label>
                                <input type="text" id="studentName" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Primary ID:</span></label>
                                <input type="text" id="primaryId" name="studbaseprim_id" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">From Campus:</span></label>
                                <input type="text" id="fromCampus" name="fromcampus" oninput="this.value = this.value.toUpperCase()"  class="form-control" readonly="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">To Campus:</span></label>
                                <select class="form-control form-control-sm" name="tocampus">
                                    @if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Arlos' || Auth::guard('web')->user()->lname == 'Gallardo' || Auth::guard('web')->user()->fname == 'Regielyn')
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
                    <button type="submit" class="btn btn-primary">Transfer Student Now</button>
                </div>
            </form>
        </div>
    </div>
</div>