<div class="modal fade" id="editgrades{{ $datagenstud->sgid }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-info-circle"></i> Confirmation
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('editGrade', ['id' => $datagenstud->sgid]) }}" id="editConfirmForm{{ $datagenstud->sgid }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" value="{{ $datagenstud->sgid }}">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="status" value="1">
                    </div>
                    Are you sure you want to Edit the Grades?
                    <div class="form-group">
                        <div class="form-row">
                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-warning">Enter the password here</span></label>
                                <input type="password" id="gradeauthpass{{ $datagenstud->sgid }}" name="gradeauthpass" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer justify-content-between">
                    <div>
                        <button type="submit" class="btn btn-primary" id="editBtn{{ $datagenstud->sgid }}" disabled>Yes</button>
                    </div>
                    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
