@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Detailed Post') }}</li>
                </ol>
            </nav>

            <img src="https://via.placeholder.com/900x400.png?text=Blog+Header" class="img-fluid rounded mb-4 w-100 shadow-sm" alt="Header">

            <div class="d-flex align-items-center text-muted mb-4 small">
                <span class="badge bg-primary text-white me-3">{{ __('Technology') }}</span>
                <i class="bi bi-calendar3 me-1"></i> <span class="me-3">{{ __('March 29, 2026') }}</span>
                <i class="bi bi-person me-1"></i> {{ __('Admin') }}
            </div>

            <h1 class="fw-bold mb-4">{{ __('Detailed view for:') }} {{ str_replace('-', ' ', Str::title($slug)) }}</h1>

            <div class="content fs-5 text-dark" style="line-height: 1.8;">
                <p>{{ __('This is a placeholder for the blog post content. In a fully implemented system, this text would be pulled dynamically from the database using the Eloquent model based on the provided slug.') }}</p>
                <p>{{ __('EduNepal is focused on transforming the educational landscape. A key component of this involves creating platforms that facilitate not only administrative work but also improve communication between staff, students, and parents.') }}</p>
                
                <h3 class="fw-bold mt-5 mb-3">{{ __('The Importance of Digital Records') }}</h3>
                <p>{{ __('Keeping digital records is not just about saving paper—it is about accessibility and security. By centralizing data in our cloud platform, institutions can retrieve student histories, grades, and behavioral records instantly.') }}</p>

                <div class="bg-light p-4 rounded border-start border-4 border-primary my-5">
                    <i class="bi bi-quote fs-3 text-primary placeholder-glow"></i>
                    <p class="lead fst-italic mb-0">{{ __('"Digital transformation in schools is no longer a luxury; it is a fundamental requirement for delivering equitable and efficient education."') }}</p>
                </div>
                
                <p>{{ __('As we continue to develop Phase 1 and beyond, our focus remains on delivering essential features seamlessly.') }}</p>
            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-between">
                <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>{{ __('Back to Blog') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
