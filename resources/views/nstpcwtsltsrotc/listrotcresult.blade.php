@extends('layouts.master_nstp')

@section('title')
CISS V.1.0 || NSTP
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">NSTP</li>
                            <li class="breadcrumb-item active mt-1">ROTC Students</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">ROTC Students</h1>
                        <p class="text-muted small mb-0">View student enrolled in NSTP - ROTC.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('rotc_nstpresult') }}" id="enrollStud">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            @if(Auth::guard('web')->user()->role == '0')
                                                <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="campus" id="campus">
                                                        <option value="MC" {{ request('campus') == 'MC' ? 'selected' : '' }}>Main</option>
                                                        <option value="VC" {{ request('campus') == 'VC' ? 'selected' : '' }}>Victorias</option>
                                                        <option value="SCC" {{ request('campus') == 'SCC' ? 'selected' : '' }}>San Carlos</option>
                                                        <option value="HC" {{ request('campus') == 'HC' ? 'selected' : '' }}>Hinigaran</option>
                                                        <option value="MP" {{ request('campus') == 'MP' ? 'selected' : '' }}>Moises Padilla</option>
                                                        <option value="IC" {{ request('campus') == 'IC' ? 'selected' : '' }}>Ilog</option>
                                                        <option value="CA" {{ request('campus') == 'CA' ? 'selected' : '' }}>Candoni</option>
                                                        <option value="CC" {{ request('campus') == 'CC' ? 'selected' : '' }}>Cauayan</option>
                                                        <option value="SC" {{ request('campus') == 'SC' ? 'selected' : '' }}>Sipalay</option>
                                                        <option value="HinC" {{ request('campus') == 'HinC' ? 'selected' : '' }}>Hinobaan</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester">
                                                    <option disabled selected>Select</option>
                                                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of NSTP - ROTC Student Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    <table id="rotctab" class="table table-hover" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                                <th>4</th>
                                                <th>5</th>
                                                <th>6</th>
                                                <th>7</th>
                                                <th>8</th>
                                                <th>9</th>
                                                <th>10</th>
                                                <th>11</th>
                                                <th>12</th>
                                                <th>13</th>
                                                <th>14</th>
                                                <th>15</th>
                                                <th>16</th>
                                                <th>17</th>
                                                <th>18</th>
                                                <th>19</th>
                                                <th>20</th>
                                                <th>21</th>
                                                <th>22</th>
                                            </tr>
                                            <tr>
                                                <th>NO.</th>
                                                <th>STUDID.</th>
                                                <th>AWARD YEAR</th>
                                                <th>NSTP Program</th>
                                                <th>REGION</th>
                                                <th>SERIAL No.</th>
                                                <th>LAST NAME</th>
                                                <th>FIRST NAME</th>
                                                <th>EXT. NAME</th>
                                                <th>MIDDLE NAME</th>
                                                <th>BIRTHDATE</th>
                                                <th>SEX</th>
                                                <th>STREET/BRGY</th>
                                                <th>TOWN/CITY</th>
                                                <th>PROVINCE</th>
                                                <th>HEI NAME</th>
                                                <th>INSTITUTIONAL</th>
                                                <th>TYPE</th>
                                                <th>PROGRAM LEVEL</th>
                                                <th>MAIN PROGRAM NAME</th>
                                                <th>EMAIL</th>
                                                <th>CONTACT NUMBER</th>
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

    <script>
        var rotcnstpReadRoute = "{{ route('getrotcnstpresult') }}";
    </script>
@endsection
