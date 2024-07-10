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
            
           <form class="form-horizontal" action="   " method="post" id="addUser">  
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">College:</span></label>
                                <select class="form-control form-control-sm" name="">
                                    <option disabled selected> --Select -- </option>
                                    @foreach($col as $datacol)
                                        <option value="{{ $datacol->college_abbr }}">{{ $datacol->college_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Department:</span></label>
                                <select class="form-control form-control-sm" name="">
                                    <option disabled selected> --Select -- </option>
                                    @foreach($dept as $datadept)
                                        <option value="{{ $datadept->college_abbr }}">{{ $datadept->deptName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Number:</span></label>
                                <input type="text" name="" class="form-control form-control-sm">
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