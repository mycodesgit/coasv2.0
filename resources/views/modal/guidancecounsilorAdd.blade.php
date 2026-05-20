<div class="modal fade" id="modal-guidanceSignatory">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add Signatory
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal" action="{{ route('signatory.create') }}" method="post" id="addGuidanceForm">  
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Name: <span class="text-danger">*</span></label>
                                <input type="text" name="fulname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Name" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Title: <span class="text-danger">*</span></label>
                                <input type="text" name="titledeg" placeholder="Enter title degree" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Position: <span class="text-danger">*</span></label>
                                <select name="position" class="form-control form-control-sm">
                                    <option disabled selected> --Select-- </option>
                                    <option value="President">President</option>
                                    <option value="Vice President">Vice President</option>
                                    <option value="Guidance Counselor">Guidance Counselor</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>School Year <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Campus: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="campus">
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
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label>Semester <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="semester">
                                    <option disabled selected>Select</option>
                                    <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>First Semester</option>
                                    <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Second Semester</option>
                                    <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Summer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>