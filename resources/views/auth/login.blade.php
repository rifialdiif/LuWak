@include('auth.head')


<body class="authentication-bg" style="background: #f8f9fa; min-height: 100vh;">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-6 p-4">
                                    <div class="mx-auto text-center mb-4">
                                        <a href="index.html">
                                            <img src="{{ asset('assets/images/bis.png') }}" alt="Logo"
                                                height="32" />
                                        </a>
                                    </div>

                                    <h6 class="h5 mb-0 mt-3">Welcome back!</h6>
                                    <p class="text-muted mt-1 mb-4">
                                        Masukkan NIP/NIM dan Password untuk mengakses sistem.
                                    </p>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <form action="{{ route('login.process') }}" method="POST"
                                        class="authentication-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">NIP/NIM</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control" id="nip_nim" name="nip_nim"
                                                    placeholder="Masukkan NIP/NIM">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Enter your password">
                                            </div>
                                        </div>

                                        <div class="mb-3 text-center d-grid">
                                            <button class="btn btn-primary" type="submit">Log In</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6 d-none d-md-inline-block">
                                    <div class="auth-page-sidebar position-relative h-100"
                                        style="background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%); min-height: 100%;">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center h-100 p-4">
                                            <!-- Logo kecil di atas -->
                                            <div class="d-flex justify-content-center mb-3" style="gap: 16px;">
                                                <img src="{{ asset('assets/images/logo_pei.png') }}" alt="Foto 1"
                                                    style="width:48px; height:48px; object-fit:cover; display: block;" />
                                                <img src="{{ asset('assets/images/trpl_nobg.png') }}" alt="Foto 2"
                                                    style="width:155px; height:48px; object-fit:cover; display: block;" />
                                                <img src="{{ asset('assets/images/logo_diktisaintek.png') }}"
                                                    alt="Foto 3" class="rounded-circle border"
                                                    style="width:48px; height:48px; object-fit:cover; background:#fff6; box-shadow:0 2px 8px rgba(0,0,0,0.07); display: block;" />
                                            </div>

                                            <!-- Gambar utama -->
                                            <img src="{{ asset('assets/images/ilustrasih.jpg') }}" alt="Pemanis"
                                                class="img-fluid rounded"
                                                style="width: 60%; max-width: 245px; min-width: 180px; height: auto; margin-bottom: 1rem; box-shadow: 0 4px 24px rgba(0,0,0,0.08); object-fit: contain; background: #fff6;" />
                                            <div style="height: 90px;"></div>

                                            <!-- Tulisan Delusi -->
                                            <div class="auth-user-testimonial text-center" style="margin-top: 1rem;">
                                                <p class="fs-24 fw-bold text-dark mb-1">DeLuSi</p>
                                                <p class="lead text-dark">"Deteksi Lulus, Bukan Sekadar Delusi"</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        @include('auth.foot')
</body>
