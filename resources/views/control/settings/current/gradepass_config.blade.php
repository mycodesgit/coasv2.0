@extends('layouts.master_settings')

@section('title')
COAS - V1.0 || Configure
@endsection

@section('sideheader')
<h4>Settings</h4>
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
            <li class="breadcrumb-item mt-1">Settings</li>
            <li class="breadcrumb-item active mt-1">Grades Password</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>

            <div class="row">
                <div class="col-md-12">
                    <table id="example1" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Grades Password</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($gradepasttngs as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $data->gradeauthpass }}</td>
                                    <td>{{ $data->updated_at->format('F d, Y h:i A') }}</td>
                                    <td style="text-align:center;">
                                        <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
                                            <i class="fas fa-cog"></i>
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
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($gradepasttngs as $data)
                <form class="form-horizontal" action="{{ route('updateGradepass', ['id' => $data->id]) }}" method="post" id="adSetConf">
                    @csrf
                    <div class="form-group">
                        <label for="editGradePassword">New Grade Password</label>
                        <input type="text" class="form-control" id="editGradePassword" value="{{ $data->gradeauthpass }}" name="gradeauthpass">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')