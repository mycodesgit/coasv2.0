<div class="modal fade" id="modal-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Add User
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
               <form class="form-horizontal" action="{{ route('userCreate') }}" method="post" id="addUser">  
                    @csrf

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">First Name:</span></label>
                                <input type="text" name="fname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter First Name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Middle Name:</span></label>
                                <input type="text" name="mname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Middle Name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Last Name:</span></label>
                                <input type="text" name="lname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Last Name" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Ext.:</span></label>
                                <select class="form-control form-control-sm" name="ext">
                                    <option value="">N/A</option>
                                    <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                    <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                    <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                    <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Email</span></label>
                                <input type="text" name="email" placeholder="Enter Email" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Password:</span></label>
                                <input type="password" name="password" placeholder="Enter Password" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Campus</span></label>
                                <select class="form-control form-control-sm" name="campus" required="">
                                    <option disabled selected>Select</option>
                                    <option value="MC" @if (old('campus') == 'MC') {{ 'selected' }} @endif>Main</option>
                                    <option value="VC" @if (old('campus') == 'VC') {{ 'selected' }} @endif>Victorias</option>
                                    <option value="SCC" @if (old('campus') == 'SCC') {{ 'selected' }} @endif>San Carlos</option>
                                    <option value="HC" @if (old('campus') == 'HC') {{ 'selected' }} @endif>Hinigaran</option>
                                    <option value="MP" @if (old('campus') == 'MP') {{ 'selected' }} @endif>Moises Padilla</option>
                                    <option value="IC" @if (old('campus') == 'IC') {{ 'selected' }} @endif>Ilog</option>
                                    <option value="CA" @if (old('campus') == 'CA') {{ 'selected' }} @endif>Candoni</option>
                                    <option value="CC" @if (old('campus') == 'CC') {{ 'selected' }} @endif>Cauayan</option>
                                    <option value="SC" @if (old('campus') == 'SC') {{ 'selected' }} @endif>Sipalay</option>
                                    <option value="HinC" @if (old('campus') == 'HinC') {{ 'selected' }} @endif>Hinobaan</option>
                                    <option value="VE" @if (old('campus') == 'VE') {{ 'selected' }} @endif>Valladolid</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Department</span></label>
                                <select class="form-control form-control-sm" name="dept">
                                    <option disabled selected>Select</option>
                                    <option value="CCS" @if (old('dept') == 'CCS') {{ 'selected' }} @endif>College of Computer Studies</option>
                                    <option value="COTED" @if (old('dept') == 'COTED') {{ 'selected' }} @endif>College of Teacher Education</option>
                                    <option value="CCJE" @if (old('dept') == 'CCJE') {{ 'selected' }} @endif>College of Criminal Justice Education</option>
                                    <option value="COE" @if (old('dept') == 'COE') {{ 'selected' }} @endif>College of Engineering</option>
                                    <option value="CAF" @if (old('dept') == 'CAF') {{ 'selected' }} @endif>College of Agriculture and Forestry</option>
                                    <option value="CBM" @if (old('dept') == 'CBM') {{ 'selected' }} @endif>College of Business Management</option>
                                    <option value="Guidance Office" @if (old('dept') == 'Guidance Office') {{ 'selected' }} @endif>Guidance Office</option>
                                    <option value="Registrar Office" @if (old('dept') == 'Registrar Office') {{ 'selected' }} @endif>Registrar Office</option>
                                    <option value="Assessment Office" @if (old('dept') == 'Assessment Office') {{ 'selected' }} @endif>Assessment Office</option>
                                    <option value="Scholarship Office" @if (old('dept') == 'Scholarship Office') {{ 'selected' }} @endif>Scholarship Office</option>
                                    <option value="Cashier Office" @if (old('dept') == 'Cashier Office') {{ 'selected' }} @endif>Cashier Office</option>
                                    <option value="Graduate School Registar" @if (old('dept') == 'Graduate School Registar') {{ 'selected' }} @endif>Graduate School Registar</option>
                                    <option value="MIS Office" @if (old('dept') == 'MIS Office') {{ 'selected' }} @endif>MIS Office</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">User Role</span></label>
                                <select class="form-control form-control-sm" name="role" >
                                    <option disabled selected>Level</option>
                                    <option value="1" @if (old('type') == 0) {{ 'selected' }} @endif>Administrator</option>
                                    <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>Guidance Officer</option>
                                    <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Guidance Staff</option>
                                    <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Registrar</option>
                                    <option value="4" @if (old('type') == 4) {{ 'selected' }} @endif>Registrar Staff</option>
                                    <option value="5" @if (old('type') == 5) {{ 'selected' }} @endif>College Dean</option>
                                    <option value="6" @if (old('type') == 6) {{ 'selected' }} @endif>Program Head</option>
                                    <option value="7" @if (old('type') == 7) {{ 'selected' }} @endif>College Staff</option>
                                    <option value="8" @if (old('type') == 8) {{ 'selected' }} @endif>Scholarship Head</option>
                                    <option value="9" @if (old('type') == 9) {{ 'selected' }} @endif>Scholarship Staff</option>
                                    <option value="10" @if (old('type') == 10) {{ 'selected' }} @endif>Assessment Head</option>
                                    <option value="11" @if (old('type') == 11) {{ 'selected' }} @endif>Assessment Staff</option>
                                    <option value="12" @if (old('type') == 12) {{ 'selected' }} @endif>MIS Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
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