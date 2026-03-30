@extends('layouts.public')

@section('content')
<div class="bg-light py-5 border-bottom">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">{{ __('Platform Features') }}</h1>
        <p class="lead text-muted">{{ __('Discover the powerful tools included in EduNepal.') }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3">{{ __('Complete Admissions Portal') }}</h2>
            <p class="text-muted lead">{{ __('Streamline your intake process with custom forms, document uploads, and automated communication.') }}</p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Online registration') }}</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Document tracking') }}</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Entrance exam management') }}</li>
            </ul>
        </div>
        <div class="col-lg-6">
            <div class="bg-light rounded p-5 text-center h-100 d-flex align-items-center justify-content-center border">
                <i class="bi bi-ui-checks text-secondary" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row g-5 align-items-center mb-5 flex-lg-row-reverse">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3">{{ __('Academics & Exams') }}</h2>
            <p class="text-muted lead">{{ __('Manage class schedules, plan lessons, and publish results instantly to the student portal.') }}</p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Dynamic timetables') }}</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Gradebook and transcripts') }}</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ __('Online assignments') }}</li>
            </ul>
        </div>
        <div class="col-lg-6">
            <div class="bg-light rounded p-5 text-center h-100 d-flex align-items-center justify-content-center border">
                <i class="bi bi-journal-bookmark-fill text-secondary" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>
</div>
@endsection
