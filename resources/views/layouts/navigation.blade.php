<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box (pasangan user info di settings)-->
        {{-- <div class="user-box text-center">
            <img src="assets/images/users/avatar-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-bs-toggle="dropdown">Nik Patel</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <a href="pages-profile.html" class="dropdown-item notify-item">
                        <i data-feather="user" class="icon-dual icon-xs me-1"></i><span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="settings" class="icon-dual icon-xs me-1"></i><span>Settings</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="help-circle" class="icon-dual icon-xs me-1"></i><span>Support</span>
                    </a>
                    <a href="pages-lock-screen.html" class="dropdown-item notify-item">
                        <i data-feather="lock" class="icon-dual icon-xs me-1"></i><span>Lock Screen</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="log-out" class="icon-dual icon-xs me-1"></i><span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div> --}}

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="activity"></i>
                        <span> Dashboards </span>
                    </a>
                </li>
                <li class="menu-title mt-2">Master</li>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i data-feather="user"></i>
                        <span> User </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('prodi.index') }}">
                        <i data-feather="layers"></i>
                        <span> Prodi </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('angkatan.index') }}">
                        <i data-feather="calendar"></i>
                        <span> Angkatan </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mhs.index') }}">
                        <i data-feather="users"></i>
                        <span> Mahasiswa </span>
                    </a>
                </li>
                <li class="menu-title mt-2">Akademik</li>
                <li>
                    <a href="{{ route('dpa.index') }}">
                        <i data-feather="user-check"></i>
                        <span> DPA </span>
                    </a>
                </li>
                <li>
                    @php
                        $user = Auth::user();
                    @endphp
                    @if ($user->role === 'mahasiswa')
                        <a href="{{ route('akademik.show', $user->mahasiswa->id_mahasiswa ?? 0) }}">
                            <i data-feather="book-open"></i>
                            <span> Akademik </span>
                        </a>
                    @else
                        <a href="{{ route('akademik.index') }}">
                            <i data-feather="book-open"></i>
                            <span> Akademik </span>
                        </a>
                    @endif
                </li>
                <li class="menu-title mt-2">Prediksi</li>
                <li>
                    <a href="#">
                        <i data-feather="trending-up"></i>
                        <span> Prediksi </span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
