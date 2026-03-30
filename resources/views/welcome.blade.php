<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
                crossorigin="anonymous"
            >
    </head>
    <body class="bg-light d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
            <div class="container">
                <span class="navbar-brand mb-0 h1 fs-4">{{ config('app.name', 'Laravel') }}</span>
                @if (Route::has('platform.login'))
                    <div class="navbar-nav ms-auto flex-row gap-2">
                        @auth('platform')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('platform.dashboard') }}">
                                Platform dashboard
                            </a>
                        @else
                            <a class="btn btn-primary btn-sm" href="{{ route('platform.login') }}">
                                Platform log in
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <main class="flex-grow-1 py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-xl-8">
                        <div class="text-center mb-4">
                            <h1 class="display-6 fw-semibold">Nepal Education Management System</h1>
                            <p class="lead text-muted mb-0">Foundation stack is ready. Start from the platform admin or the docs below.</p>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="h5 mb-3">Get started</h2>
                                <p class="text-muted mb-4">Useful links while you develop:</p>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge bg-primary rounded-pill me-3 mt-1">1</span>
                                        <span>
                                            Read the
                                            <a href="https://laravel.com/docs" target="_blank" rel="noopener noreferrer" class="fw-medium">Laravel documentation</a>
                                        </span>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge bg-primary rounded-pill me-3 mt-1">2</span>
                                        <span>
                                            Watch tutorials at
                                            <a href="https://laracasts.com" target="_blank" rel="noopener noreferrer" class="fw-medium">Laracasts</a>
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-start">
                                        <span class="badge bg-primary rounded-pill me-3 mt-1">3</span>
                                        <span>
                                            Review the
                                            <a href="https://github.com/laravel/laravel/blob/13.x/CHANGELOG.md" target="_blank" rel="noopener noreferrer" class="fw-medium">Laravel changelog</a>
                                        </span>
                                    </li>
                                </ul>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="https://cloud.laravel.com" target="_blank" rel="noopener noreferrer" class="btn btn-dark">
                                        Deploy to Laravel Cloud
                                    </a>
                                </div>

                                <hr class="my-4">

                                <p class="small text-muted mb-0">
                                    Laravel v{{ app()->version() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-4 border-top bg-white mt-auto">
            <div class="container text-center small text-muted">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </div>
        </footer>

            <script
                src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous"
            ></script>
    </body>
</html>
