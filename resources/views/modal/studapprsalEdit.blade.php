<div class="modal fade" id="btneditappModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pen"></i> Edit Student Appraisal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <table id="curapprsledit" class="table table-hover table-striped" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Fund</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('stateaccntpersem_getsearchCreate') }}" method="POST" id="adstudfeesmissing">
                            @csrf
                            <input type="hidden" name="studID" value="{{ request('stud_id') }}">
                            <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                            <input type="hidden" name="semester" value="{{ request('semester') }}">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="dateAssess" value="{{ date('Y-m-d') }}">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label for="fundidcode">Fund Code</label>
                                        <input type="text" name="fundID" id="fundidcode" class="form-control form-control-sm" value="164" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label>Account</label>
                                        <select class="form-control form-control-sm select2bs4" data-placeholder="--Select--" name="account">
                                            @foreach($studAccntap as $studapp)
                                                <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label>Amount</label>
                                        <input type="number" name="amount" class="form-control form-control-sm" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>