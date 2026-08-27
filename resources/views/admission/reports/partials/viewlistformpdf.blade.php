<div class="card-body">
    @foreach($applicant as $data) @endforeach
    <iframe src="{{ route('applicant.viewPDFform1', ['id' => $applicant->id]) }}" width="100%" height="500"></iframe>
</div>