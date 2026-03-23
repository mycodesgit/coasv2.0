<div class="modal fade mt-6" id="modal-allsch">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add Scholarship
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
               <form class="form-horizontal" action="{{ route('allscholarCreate') }}" method="post" id="addScholar">  
                    @csrf

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Scholarship Name: <span class="text-danger">*</span></label>
                                <input type="text" name="scholar_name" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Scholarship Name" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Scholarship Sponsor: <span class="text-danger">*</span></label>
                                <input type="text" name="scholar_sponsor" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Scholarship Sponsor" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>CHED Scholarship Category: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="chedcategory">
                                    <option disabled selected>--Select--</option>
                                    @foreach($ched as $datached)
                                        <option value="{{ $datached->id }}">{{ $datached->chedsch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>CPSU Scholarship Category: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="unicategory">
                                    <option disabled selected>--Select--</option>
                                    @foreach($uni as $datauni)
                                        <option value="{{ $datauni->id }}">{{ $datauni->unisch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Funding Source: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="fund_source">
                                    <option disabled selected>--Select--</option>
                                    @foreach($fs as $datafs)
                                        <option value="{{ $datafs->id }}">{{ $datafs->fndsource_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-success">
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