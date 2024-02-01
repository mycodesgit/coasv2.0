<div class="modal fade" id="info_app" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-info-circle"></i> Select Transaction
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                @if (Auth::user()->isAdmin == 5 || Auth::user()->isAdmin == 6 || Auth::user()->isAdmin == 7)
                    <div>
                        <a href="#" type="button" class="btn btn-primary" id="btn_dept_interview">
                            <i class="fas fa-pen"></i>
                        </a> <span style="font-size: 13pt">Process Pre-Enrollment</span>
                    </div>
                    <div class="mt-1">
                        <a href="#" type="button" class="btn btn-primary" id="btn_view_preenrolment">
                            <i class="fas fa-print"></i>    
                        </a> <span style="font-size: 13pt">Generate Pre-Enrollment</span>
                    </div>
                    {{-- <div class="mt-1">
                        <a href="#" type="button" class="btn btn-primary" id="btn_accepted_applicants">
                            <i class="fas fa-chart-simple"></i>
                        </a> <span style="font-size: 13pt">Accept Applicant</span>
                    </div> --}}
                @else
                    <div class="mt-1">
                        <a href="#" type="button" class="btn btn-primary" id="btn_view_preenrolment">
                            <i class="fas fa-print"></i>    
                        </a> <span style="font-size: 13pt">Generate Pre-Enrollment</span>
                    </div>
                    <div class="mt-1">
                        <a href="#" type="button" class="btn btn-primary" id="btn_accepted_applicants">
                            <i class="fas fa-check"></i>
                        </a> <span style="font-size: 13pt">Accept Applicant</span>
                    </div>
                @endif
            </div>
            
            <div class="modal-footer justify-content-between">
                <div></div>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>