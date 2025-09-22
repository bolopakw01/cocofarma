<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('pageTitle', $pageTitle ?? 'Backoffice')</title>

    <!-- Central admin UI CSS (includes Google Fonts Poppins and base variables) -->
    <link rel="stylesheet" href="{{ asset('bolopa/back/css/admin-ui.css') }}">

    <!-- Favicon (tab icon) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    @yield('styles')

    <style>
        /* Loading Animation Styles */
        #page {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        #h3 {
            color: white;
        }

        #ring {
            width: 190px;
            height: 190px;
            border: 1px solid transparent;
            border-radius: 50%;
            position: absolute;
        }

        #ring:nth-child(1) {
            border-bottom: 8px solid rgb(255, 141, 249);
            animation: rotate1 2s linear infinite;
        }

        @keyframes rotate1 {
            from {
                transform: rotateX(50deg) rotateZ(110deg);
            }
            to {
                transform: rotateX(50deg) rotateZ(470deg);
            }
        }

        #ring:nth-child(2) {
            border-bottom: 8px solid rgb(255,65,106);
            animation: rotate2 2s linear infinite;
        }

        @keyframes rotate2 {
            from {
                transform: rotateX(20deg) rotateY(50deg) rotateZ(20deg);
            }
            to {
                transform: rotateX(20deg) rotateY(50deg) rotateZ(380deg);
            }
        }

        #ring:nth-child(3) {
            border-bottom: 8px solid rgb(0,255,255);
            animation: rotate3 2s linear infinite;
        }

        @keyframes rotate3 {
            from {
                transform: rotateX(40deg) rotateY(130deg) rotateZ(450deg);
            }
            to {
                transform: rotateX(40deg) rotateY(130deg) rotateZ(90deg);
            }
        }

        #ring:nth-child(4) {
            border-bottom: 8px solid rgb(252, 183, 55);
            animation: rotate4 2s linear infinite;
        }

        @keyframes rotate4 {
            from {
                transform: rotateX(70deg) rotateZ(270deg);
            }
            to {
                transform: rotateX(70deg) rotateZ(630deg);
            }
        }
    </style>

    @stack('styles')
</head>
<body style="margin: 0; min-height: 100vh; display: flex; flex-direction: column; background: #e4e9f7;">
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <section class="home-section" style="flex: 1; display: flex; flex-direction: column;">
        <!-- Header -->
        @include('admin.partials.header')

    <!-- Page Content -->
    <main class="main-content" style="flex: 1; position: relative; padding-top: 20px; padding-bottom: 20px;">
            <div id="loading-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); display: flex; justify-content: center; align-items: center; z-index: 9999; backdrop-filter: blur(2px);">
                <div id="page">
                    <div id="container">
                        <div id="ring"></div>
                        <div id="ring"></div>
                        <div id="ring"></div>
                        <div id="ring"></div>
                        <div id="h3">loading</div>
                    </div>
                </div>
            </div>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('admin.partials.footer')
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Global Success/Error Message Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle success messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '<i class="fas fa-check-circle text-success"></i> Berhasil!',
                    html: '<strong>{{ session('success') }}</strong>',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fas fa-check-circle"></i> OK',
                    confirmButtonColor: '#28a745',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            @endif

            // Handle error messages
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '<i class="fas fa-exclamation-triangle text-danger"></i> Error!',
                    html: '<strong>{{ session('error') }}</strong>',
                    confirmButtonText: '<i class="fas fa-redo"></i> OK',
                    confirmButtonColor: '#dc3545',
                    allowOutsideClick: true
                });
            @endif

            // Handle info messages
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: '<i class="fas fa-info-circle text-info"></i> Info!',
                    html: '<strong>{{ session('info') }}</strong>',
                    confirmButtonText: '<i class="fas fa-check-circle"></i> OK',
                    confirmButtonColor: '#17a2b8',
                    allowOutsideClick: true
                });
            @endif

            // Handle warning messages
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: '<i class="fas fa-exclamation-triangle text-warning"></i> Peringatan!',
                    html: '<strong>{{ session('warning') }}</strong>',
                    confirmButtonText: '<i class="fas fa-check-circle"></i> OK',
                    confirmButtonColor: '#ffc107',
                    allowOutsideClick: true
                });
            @endif
        });

        // Control loading overlay - only show on first visit
        window.addEventListener('load', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                // Check if this is the first visit to this page
                const pageKey = window.location.pathname;
                const hasVisited = localStorage.getItem('visited_' + pageKey);

                if (hasVisited) {
                    // Not first visit - hide loading immediately
                    loadingOverlay.style.display = 'none';
                } else {
                    // First visit - show loading for minimum duration then hide
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                        // Mark as visited
                        localStorage.setItem('visited_' + pageKey, 'true');
                    }, 1500);
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
