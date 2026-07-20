@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Faculty List</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Faculty List</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mt-3 p-2">
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                                            <h5>Add</h5>
                                                        </div>

                                                        <div class="mt-2 col-md-12">
                                                            <label>Is Newly Hired? <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" id="isNew">
                                                                <option disabled selected>-- Select --</option>
                                                                <option value="yes">Newly Hired</option>
                                                                <option value="no">Existing Faculty</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div id="newFacultyForm" style="display:none;">
                                                            <form method="post" action="{{ route('facultyCreate') }}" id="adFac">
                                                                @csrf
                                                                <div class="form-group mt-3">
                                                                    <div class="row g-3">
                                                                        <div class="mt-2 col-md-12">
                                                                            <label>College: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="faccollege" id="college">
                                                                                <option disabled selected> ---Select---</option>
                                                                                @foreach($collegelist as $datacollegelist)
                                                                                    <option value="{{ $datacollegelist->college_abbr }}">
                                                                                        {{ $datacollegelist->college_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Department: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="facdept" id="department">
                                                                                <option disabled selected> ---Select---</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-2 col-md-12" id="deptDropdownContainer" style="display: none; margin-top: 10px;">
                                                                            <label for="deptprogmajor">Major: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" id="deptprogmajor" name="deptmajor">
                                                                                <option value=""> --Select Major-- </option>
                                                                                <option value="English">English</option>
                                                                                <option value="Filipino">Filipino</option>
                                                                                <option value="Math">Math</option>
                                                                                <option value="Science">Science</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Lastname: <span class="text-danger">*</span></label>
                                                                            <input type="text" name="lname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" class="form-control form-control-sm">
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Firstname: <span class="text-danger">*</span></label>
                                                                            <input type="text" name="fname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" class="form-control form-control-sm">
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Middle initial: </label>
                                                                            <input type="text" name="mname" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Ext: </label>
                                                                            <input type="text" name="ext" class="form-control form-control-sm">
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Prefix:</label>
                                                                            <select class="form-control form-control-sm" name="prefix">
                                                                                <option disabled selected> --Select-- </option>
                                                                                @foreach($adr as $dataadr)
                                                                                    <option value="{{ $dataadr->id }}">{{ $dataadr->adrDesc }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Suffix:</label>
                                                                            <select class="form-control form-control-sm" name="suffix">
                                                                                <option disabled selected> --Select-- </option>
                                                                                <option value="">Select Suffix</option>
                                                                                <option value="Ph.D.">Ph.D.</option>
                                                                                <option value="Ed.D.">Ed.D.</option>
                                                                                <option value="MIT">MIT</option>
                                                                                <option value="MSIT">MSIT</option>
                                                                                <option value="MA">MA</option>
                                                                                <option value="MBA">MBA</option>
                                                                                <option value="CPA">CPA</option>
                                                                                <option value="MEd">MEd</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-2 col-md-12">
                                                                            <label>Email: <span class="text-danger">*</span></label>
                                                                            <input type="email" name="email" class="form-control form-control-sm">
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <label>&nbsp;</label>
                                                                            <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div id="existingFaculty" style="display:none;" class="mt-3">
                                                            <form id="updateCampusForm">
                                                                @csrf

                                                                <div class="row g-3">
                                                                    <div class="mt-2 col-md-12">
                                                                        <label>Select Faculty:</label>
                                                                        <select class="form-control form-control-sm"
                                                                                id="facultySelect"
                                                                                name="faculty_id">
                                                                        </select>
                                                                    </div>
                                                                    <div class="mt-2 col-md-12">
                                                                        <label>Transfer/Assign to Campus:</label>
                                                                        <!-- Hidden input for actual value -->
                                                                        <input type="hidden" name="campus" id="campusHidden" value="{{ Auth::guard('web')->user()->campus }}">
                                                                        <input type="hidden" name="campactive" id="campactiveHidden" value="{{ Auth::guard('web')->user()->campus }}">
                                                                        <!-- Visible input for display -->
                                                                        @php
                                                                            $campuses = [
                                                                                'MC'   => 'Main',
                                                                                'VC'   => 'Victorias',
                                                                                'SCC'  => 'San Carlos',
                                                                                'HC'   => 'Hinigaran',
                                                                                'MP'   => 'Moise Padilla',
                                                                                'IC'   => 'Ilog',
                                                                                'CA'   => 'Candoni',
                                                                                'CC'   => 'Cauayan',
                                                                                'SC'   => 'Sipalay',
                                                                                'HinC' => 'Hinobaan',
                                                                            ];

                                                                            $userCampus = Auth::guard('web')->user()->campus;
                                                                            $campusName = $campuses[$userCampus] ?? '';
                                                                        @endphp
                                                                        <input type="text" id="campusInput" class="form-control form-control-sm" value="{{ $campusName }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label>&nbsp;</label>
                                                                        <button type="submit" id="saveCampusBtn" class="btn btn-success btn-sm btn-block">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9 mt-3">
                                                <table id="facltyTable" class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Salutation</th>
                                                            <th>College</th>
                                                            <th>Dept</th>
                                                            <th>Rank</th>
                                                            <th>Campus</th>
                                                            <th>Active Campus</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editFacultyModal" role="dialog" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFacultyForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editFacultyId">
                        <div class="form-group mt-3">
                            <label for="editcollege">College: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editcollege" name="faccollege">
                                <option disabled selected> --Select--  </option>
                                @foreach($collegelist as $datacollegelist)
                                    <option value="{{ $datacollegelist->college_abbr }}">{{ $datacollegelist->college_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editdept">Department: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editdept" name="facdept"  onchange="toggleSedDropdown()">
                                <option disabled selected> --Select-- </option>
                                @foreach($depts as $datadepts)
                                    <option value="{{ $datadepts->deptCod }}">{{ $datadepts->deptName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Additional dropdown for SED department -->
                        <div class="form-group mt-3" id="sedDropdownContainer" style="display: none; margin-top: 10px;">
                            <label for="sedProgram">Major: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="sedProgram" name="deptmajor">
                                <option value=""> --Select Major-- </option>
                                <option value="English">English</option>
                                <option value="Filipino">Filipino</option>
                                <option value="Math">Math</option>
                                <option value="Science">Science</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editLastname">Lastname: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editLastname" name="lname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editFirstname">Firstname: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editFirstname" name="fname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editMiddlename">Middlename: </label>
                            <input type="text" class="form-control form-control-sm" id="editMiddlename" name="mname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editExtname">Ext:</label>
                            <input type="number" class="form-control form-control-sm" id="editExtname" name="ext">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editPrefix">Prefix:</label>
                            <select class="form-control form-control-sm" id="editPrefix" name="prefix">
                                <option disabled selected> --Select-- </option>
                                @foreach($adr as $dataadr)
                                    <option value="{{ $dataadr->id }}">{{ $dataadr->adrDesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSuffix">Suffix:</label>
                            <select class="form-control form-control-sm" id="editSuffix" name="suffix">
                                <option disabled selected> --Select-- </option>
                                <option value="">Select Suffix</option>
                                <option value="Ph.D.">Ph.D.</option>
                                <option value="Ed.D.">Ed.D.</option>
                                <option value="MIT">MIT</option>
                                <option value="MSIT">MSIT</option>
                                <option value="MA">MA</option>
                                <option value="MBA">MBA</option>
                                <option value="CPA">CPA</option>
                                <option value="MEd">MEd</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editEmail">Email: <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm" id="editEmail">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editEmail">Academic Rank: <span class="text-danger">*</span></label>
                            <select name="rank" id="eeditacadrank" class="form-control form-control-sm">
                                <option disabled selected> --Select Rank-- </option>
                                <option value=""> --None-- </option>
                                <option value="Professor VI">Professor VI</option>
                                <option value="Professor V">Professor V</option>
                                <option value="Professor IV">Professor IV</option>
                                <option value="Professor III">Professor III</option>
                                <option value="Professor II">Professor II</option>
                                <option value="Professor I">Professor I</option>
                                <option value="Associate Professor V">Associate Professor V</option>
                                <option value="Associate Professor IV">Associate Professor IV</option>
                                <option value="Associate Professor III">Associate Professor III</option>
                                <option value="Associate Professor II">Associate Professor II</option>
                                <option value="Associate Professor I">Associate Professor I</option>
                                <option value="Assistant Professor IV">Assistant Professor IV</option>
                                <option value="Assistant Professor III">Assistant Professor III</option>
                                <option value="Assistant Professor II">Assistant Professor II</option>
                                <option value="Assistant Professor I">Assistant Professor I</option>
                                <option value="Instructor III">Instructor III</option>
                                <option value="Instructor II">Instructor II</option>
                                <option value="Instructor I">Instructor I</option>
                                <option value="Part-Time">Part-Time</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var facultyReadRoute = "{{ route('getfacultylistRead') }}";
        var facultyCreateRoute = "{{ route('facultyCreate') }}";
        var facultyUpdateRoute = "{{ route('facultyUpdate', ['id' => ':id']) }}";
        var facultyDeleteRoute = "{{ route('facultyDelete', ['id' => ':id']) }}";
        var roomidEncryptRoute = "{{ route('idcrypt') }}";
        var getdepartmentRoute = "{{ route('getDepartments', ':college') }}";
        var searchFacultyRoute = "{{ route('faculty.search') }}";
        var campusUpdateRoute = "{{ route('faculty.updateCampus', ['faculty' => ':id']) }}";
    </script>
@endsection
