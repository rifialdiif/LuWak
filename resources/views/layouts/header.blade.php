@if (session('welcome'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div class="toast" id="welcomeToast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
            <div class="toast-header d-flex align-items-center">
                <img src="{{ asset('assets/images/delusi 5.png') }}" class="rounded me-2" alt="Logo"
                    style="width: 20px; height: 20px;">
                <div class="flex-grow-1"></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('welcome') }}
            </div>
        </div>
    </div>
@endif

<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">

            <li class="dropdown d-inline-block d-lg-none">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i data-feather="search"></i>
                </a>
                <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="Search ..." aria-label="search here">
                    </form>
                </div>
            </li>

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="fullscreen" href="#">
                    <i data-feather="maximize"></i>
                </a>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user me-0 d-flex align-items-center" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false"
                    style="padding: 0.5rem 1rem;">
                    <img src="{{ asset('assets/images/pengguna.png') }}" alt="user-image" class="rounded-circle"
                        style="width: 36px; height: 36px;">
                    <div class="ms-2 text-start">
                        <div class="fw-semibold" style="line-height: 1.2;">
                            {{ Auth::user()->nama }}
                        </div>
                        <div class="text-muted small" style="line-height: 1;">
                            {{ ucfirst(Auth::user()->role) }} - {{ ucfirst(Auth::user()->prodi->nama_prodi) }}
                        </div>
                    </div>
                    <i class="uil uil-angle-down ms-2"></i>
                </a>
                {{-- Dibawah buat percobaan --}}
                {{-- <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="{{ asset('assets/images/avatar-1.png') }}" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name">Mas Voldi</span>
                    <span class="account-position">Admin</span>
                </span>
            </a> --}}
                <div
                    class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome, {{ Auth::user()->nama }}!</h6>
                    </div>
                    <a href="{{ route('profile.index') }}" class="dropdown-item notify-item">
                        <i data-feather="user" class="icon-dual icon-xs me-1"></i><span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="#" class="dropdown-item notify-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out" class="icon-dual icon-xs me-1"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

            <li class="dropdown notification-list">
                <a href="javascript:void(0);" class="nav-link right-bar-toggle">
                    <i data-feather="settings"></i>
                </a>
            </li>
        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="#" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/delusi 5.png') }}" alt="" height="24">
                    <!-- <span class="logo-lg-text-light">Shreyu</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/bis.png') }}" alt="" height="24">
                    <!-- <span class="logo-lg-text-light">S</span> -->
                </span>
            </a>

            <a href="#" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/delusi 5.png') }}" alt="" width="34" height="48">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/bis.png') }}" alt="" height="24">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile">
                    <i data-feather="menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('welcomeToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 4000
                });
                toast.show();
            }
        });
    </script>
@endpush
