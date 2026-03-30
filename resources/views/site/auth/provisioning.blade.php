@extends('layouts.public')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </div>
            
            <h2 class="fw-bold mt-4">{{ __('Provisioning Your Environment') }}</h2>
            <p class="text-muted lead mt-3">{{ __('Please wait while we set up your database, subdomains, and initialize your platform...') }}</p>
            
            <div class="progress mt-4 mx-auto" style="height: 10px; max-width: 300px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <p class="text-secondary small mt-4">{{ __('This usually takes less than a minute.') }}</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkStatus = () => {
            fetch('{{ route('site.provisioning.status') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'trial' || data.status === 'active') {
                        // Redirect to the success URL returned
                        window.location.href = data.redirect_url || '/profile';
                    } else if (data.status === 'failed') {
                        // Stop polling and show error
                        clearInterval(pollInterval);
                        alert('Provisioning failed. Please contact support or try again.');
                        window.location.href = '/register';
                    }
                })
                .catch(err => console.error("Polling error:", err));
        };

        // Poll every 3 seconds
        const pollInterval = setInterval(checkStatus, 3000);
    });
</script>
@endpush
@endsection
