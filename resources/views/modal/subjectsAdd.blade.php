<div class="modal fade" id="modal-subjects">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
           <form class="form-horizontal" action=" " method="post" id="addUser">  
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">College:</span></label>
                                <select class="form-control form-control-sm" id="college" name="college">
                                    <option disabled selected>--Select--</option>
                                    @foreach($col as $datacol)
                                        <option value="{{ $datacol->college_abbr }}">{{ $datacol->college_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Department:</span></label>
                                <select class="form-control form-control-sm" id="department" name="deptCod">
                                    <option disabled selected>--Select--</option>
                                    @foreach($dept as $datadept)
                                        <option value="{{ $datadept->deptCod }}">{{ $datadept->deptName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Subject Code:</span></label>
                                <input type="text" id="sub_code" name="sub_code" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Cost Center:</span></label>
                                <input type="text" id="subjcostcenter" name="subjcostcenter" class="form-control form-control-sm" readonly>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-info">Subject Name:</span></label>
                                <input type="text" id="sub_name" name="sub_name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-info">Subject Description:</span></label>
                                <input type="text" id="sub_title" name="sub_title" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-1">
                                <label><span class="badge badge-warning">Lecture Unit:</span></label>
                                <input type="number" id="sublecredit" name="sublecredit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-1">
                                <label><span class="badge badge-warning">Laboratory Unit:</span></label>
                                <input type="number" id="sublabcredit" name="sublabcredit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-warning">Total Unit:</span></label>
                                <input type="number" id="sub_unit" name="sub_unit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">No. of Weeks:</span></label>
                                <input type="number" id="subjweeks" name="subjweeks" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Hours/Weeks:</span></label>
                                <input type="number" id="subjconthrs" name="subjconthrs" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Level</span></label>
                                <select class="form-control form-control-sm" id="studLevel" name="studLevel">
                                    <option disabled selected>--Select--</option>
                                    @foreach($lev as $datalev)
                                        <option value="{{ $datalev->id }}">{{ $datalev->studLevel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Delivery Mode</span></label>
                                <select class="form-control form-control-sm" id="studLevel" name="studLevel">
                                    <option disabled selected>--Select--</option>
                                    @foreach($delv as $datadelv)
                                        <option value="{{ $datadelv->id }}">{{ $datadelv->delmode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>