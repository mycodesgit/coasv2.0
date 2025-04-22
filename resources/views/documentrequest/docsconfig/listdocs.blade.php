@extends('layouts.master_documents')

@section('title')
CISS V.1.0 || List of Document
@endsection

@section('sideheader')
<h4>Document Req</h4>
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
            <li class="breadcrumb-item mt-1">Document Request</li>
            <li class="breadcrumb-item active mt-1">List of Document</li>
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
                <h4>List of Document</h4>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-3 mt-3">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('fundCreate') }}" id="adDocs">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add Document</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Document Name</span></label>
                                        <input type="text" name="docname" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9 mt-3">
                <table id="docslist" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDocsModal" tabindex="-1" role="dialog" aria-labelledby="editDocsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocsModalLabel">Edit Document Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDocsForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editDocsId">
                    <div class="form-group">
                        <label for="editDocsName">Document Name</label>
                        <input type="text" class="form-control" id="editDocsName" name="docname">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var docsreqReadRoute = "{{ route('getdocsRead') }}";
    var docsreqCreateRoute = "{{ route('docsCreate') }}";
    var docsreqUpdateRoute = "{{ route('docsUpdate', ['id' => ':id']) }}";
</script>

@endsection
