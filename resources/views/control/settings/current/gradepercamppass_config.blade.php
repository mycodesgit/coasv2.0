@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
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
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">Grades Password</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Grades Password</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        @if(Session::has('success'))
                                            <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                        @endif
                                    
                                        <div class="table-responsive p-3 mt-3">
                                            <table id="setconftable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Grades Password</th>
                                                        <th>Campus</th>
                                                        <th>Date Created</th>
                                                        <th>Date Updated</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach($gradepasttngs as $data)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $data->gradeauthpass }}</td>
                                                            <td>{{ $data->campus }}</td>
                                                            <td>{{ $data->created_at->format('F d, Y h:i A') }}</td>
                                                            <td>{{ $data->updated_at->format('F d, Y h:i A') }}</td>
                                                            <td>
                                                                <a href="#" type="button" class="btn btn-success btn-sm text-light" data-bs-toggle="modal" data-bs-target="#editGradePassModal" data-id="{{ $data->id }}" data-password="{{ $data->gradeauthpass }}">
                                                                    <i class="ti ti-settings-cog"></i>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGradePassModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="editGradePassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGradePassModalLabel">Edit Grades Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" id="adSetConf">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editSetConfId">
                        
                        <div class="form-group mt-3">
                            <label for="editGradePassword">New Grade Password: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editGradePassword" name="gradeauthpass">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let updateUrlTemplate = "{{ route('updateGradepass', ':id') }}";

        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('editGradePassModal');

            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                var id = button.getAttribute('data-id');
                var password = button.getAttribute('data-password');

                // Set values in modal
                document.getElementById('editSetConfId').value = id;
                document.getElementById('editGradePassword').value = password;

                // Update form action dynamically
                var form = document.getElementById('adSetConf');
                form.action = updateUrlTemplate.replace(':id', id);
            });
        });
    </script>
@endsection
