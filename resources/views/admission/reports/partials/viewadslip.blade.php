<div class="card-body">
    @foreach($applicant as $data) @endforeach
    <iframe src="{{ route('applicantadslipPDF_reports', ['id' => $applicant->id]) }}" width="100%" height="500"></iframe>
</div>