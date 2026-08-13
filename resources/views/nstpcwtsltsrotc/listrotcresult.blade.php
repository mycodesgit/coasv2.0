@extends('layouts.master_nstp')

@section('title')
CISS V.1.0 || NSTP
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
                            <li class="breadcrumb-item mt-1">NSTP</li>
                            <li class="breadcrumb-item active mt-1">ROTC Students</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>ROTC Students</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('rotc_nstpresult') }}" id="enrollStud">
                                            @csrf   

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    @if(Auth::guard('web')->user()->role == '0')
                                                        <div class="col-md-2">
                                                            <label>Campus: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="campus" id="campus">
                                                                <option value="MC">Main</option>
                                                                <option value="SCC">San Carlos</option>
                                                                <option value="VC">Victorias</option>
                                                                <option value="HC">Hinigaran</option>
                                                                <option value="MP">Moises Padilla</option>
                                                                <option value="HinC">Hinobaan</option>
                                                                <option value="SC">Sipalay</option>
                                                                <option value="IC">Ilog</option>
                                                                <option value="CC">Cauayan</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-3 p-2">
                                                    <table id="rotctab" class="table table-hover">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        var rotcnstpReadRoute = "{{ route('getrotcnstpresult') }}";
    </script>
@endsection
