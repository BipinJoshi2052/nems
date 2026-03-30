@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-center mb-4">{{ __('Contact Us') }}</h1>
            <p class="text-center text-muted mb-5">{{ __('Have a question or want to request a demo? Fill out the form below and our team will get back to you shortly.') }}</p>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Your Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-12">
                                <label for="institution" class="form-label">{{ __('Institution Name') }}</label>
                                <input type="text" class="form-control" id="institution" name="institution">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">{{ __('Message') }}</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">{{ __('Send Message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
