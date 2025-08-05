@extends('layouts.master_nstp')

@section('title')
CISS V.1.0 || NSTP CWTS
@endsection

@section('sideheader')
<h4>NSTP</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">NSTP</li>
            <li class="breadcrumb-item active mt-1">CWTS</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>CWTS</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('cwts_nstpresult') }}" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2" style="padding: 10px">
                            <div class="form-row">
                                @if(Auth::guard('web')->user()->role == '0')
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Campus</span></label>
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
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Semester</span></label>
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
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover">
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
                            <tbody>
                                @foreach($substudnowviewpdf as $claEn)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $claEn->studID }}</td>
                                        <td>{{ $claEn->schlyear }}</td>
                                        <td>{{ $claEn->sub_name }}</td>
                                        <td>{{ $claEn->region }}</td>
                                        <td></td>
                                        <td>{{ $claEn->lname }}</td>
                                        <td>{{ $claEn->fname }}</td>
                                        <td>{{ $claEn->ext == 'N/A' ? '' : $claEn->ext }}</td>
                                        <td>{{ $claEn->mname }}</td>
                                        <td>{{ $claEn->bday }}</td>
                                        <td>{{ $claEn->gender }}</td>
                                        <td>{{ $claEn->brgy }}</td>
                                        <td>{{ $claEn->city }}</td>
                                        <td>{{ $claEn->province }}</td>
                                        <td>CPSU</td>
                                        <td>6058</td>
                                        <td>{{ $claEn->course }}</td>
                                        <td></td>
                                        <td>{{ $claEn->course }}</td>
                                        <td>{{ $claEn->email }}</td>
                                        <td>{{ $claEn->contact }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
