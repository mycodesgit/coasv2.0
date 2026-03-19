<div class="modal fade mt-6" id="modal-subjects">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
           <form class="form-horizontal" action="{{ route('subjectsCreate') }}" method="post" id="addSubject">  
                @csrf
                <div class="modal-body">
                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>College: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="college" name="college">
                                    <option disabled selected>--Select--</option>
                                    @foreach($col as $datacol)
                                        <option value="{{ $datacol->college_abbr }}">{{ $datacol->college_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Department: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="department" name="deptCod">
                                    <option disabled selected>--Select--</option>
                                    @foreach($dept as $datadept)
                                        <option value="{{ $datadept->deptCod }}">{{ $datadept->deptName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Subject Code: <span class="text-danger">*</span></label>
                                <input type="text" id="sub_code" name="sub_code" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Cost Center: <span class="text-danger">*</span></label>
                                <input type="text" id="subjcostcenter" name="subjcostcenter" class="form-control form-control-sm" readonly>
                            </div>

                            <div class="col-md-4">
                                <label>Subject Name: <span class="text-danger">*</span></label>
                                <input type="text" id="sub_name" name="sub_name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label>Subject Description: <span class="text-danger">*</span></label>
                                <input type="text" id="sub_title" name="sub_title" class="form-control form-control-sm">
                            </div>
                            <input type="hidden" id="subjcollege" name="subjcollege" class="form-control form-control-sm">
                            <input type="hidden" id="subjdep" name="subjdep" class="form-control form-control-sm">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-1">
                                <label>Lec. Unit: <span class="text-danger">*</span></label>
                                <input type="number" id="sublecredit" name="sublecredit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-1">
                                <label>Lab. Unit: <span class="text-danger">*</span></label>
                                <input type="number" id="sublabcredit" name="sublabcredit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label>Total Unit: <span class="text-danger">*</span></label>
                                <input type="number" id="sub_unit" name="sub_unit" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label>No. of Weeks: <span class="text-danger">*</span></label>
                                <input type="number" id="subjweeks" name="subjweeks" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label>Hours/Weeks: <span class="text-danger">*</span></label>
                                <input type="number" id="subjconthrs" name="subjconthrs" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label>Level <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="subjlev" name="subjlev">
                                    <option disabled selected>--Select--</option>
                                    @foreach($lev as $datalev)
                                        <option value="{{ $datalev->id }}">{{ $datalev->studLevel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Delivery Mode <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="subdelmod" name="subdelmod">
                                    <option disabled selected>--Select--</option>
                                    @foreach($delv as $datadelv)
                                        <option value="{{ $datadelv->id }}">{{ $datadelv->delmode }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Subject Pre-requisite <span class="text-danger">*</span></label>
                                <input type="text" id=" subjprereq" name="subjprereq" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label>Subject Co-requisite <span class="text-danger">*</span></label>
                                <input type="text" id=" subjcoreq" name=" subjcoreq" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Academic Type <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="subacadtype" name="subacadtype">
                                    <option disabled selected>--Select--</option>
                                    @foreach($acad as $dataacad)
                                        <option value="{{ $dataacad->id }}">{{ $dataacad->acadtype_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>