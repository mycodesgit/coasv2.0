<div class="modal fade" id="developmentModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    role="dialog"
    aria-labelledby="developmentModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="developmentModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Notice
                </h5>
            </div>

            <div class="modal-body text-center">
                <h4 class="mb-3">Feature Temporarily Unavailable</h4>

                <p>
                    The admission module is currently undergoing updates and improvements.
                    We appreciate your patience while we enhance the system.
                </p>

                <p class="mb-4">
                    Please return to the home page and try again soon.
                </p>

                <a href="{{ route('home') }}" class="btn btn-success">
                    <i class="fas fa-home"></i> Go Back to Home Page
                </a>
            </div>

        </div>
    </div>
</div>