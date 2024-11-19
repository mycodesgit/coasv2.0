@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('homefaculty') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Gradesheet</li>
            <li class="breadcrumb-item active mt-1">Semester</li>
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
                <h4>Grade Sheet</h4>
            </div>
        </div>

        <div class="mt-5 row">
            <div class="col-md-12">
                <table id="example1" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Semester</th>
                            <th>School Year</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($progen as $dataprogen)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                @if($dataprogen->semester == 1)
                                    1st Semester
                                @elseif($dataprogen->semester == 2)
                                    2nd Semester
                                @elseif($dataprogen->semester == 3)
                                    Summer
                                @else
                                    Unknown Semester
                                @endif
                            </td>
                            <td>{{ $dataprogen->schlyear }}</td>
                            <td>
                                <a href="{{ route('virtualfaculty_class', ['semester' => $dataprogen->semester, 'schlyear' => $dataprogen->schlyear]) }}" type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>



@endsection
