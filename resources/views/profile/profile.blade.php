@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informasi User -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Informasi User</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>NIM/NIP:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ auth()->user()->nip_nim }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nama:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ auth()->user()->nama }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Program Studi:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ auth()->user()->prodi ? auth()->user()->prodi->nama_prodi : 'Tidak ada' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-md-8">
                            <span
                                class="badge bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'dosen' ? 'primary' : 'success') }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Ubah Password -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Ubah Password</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    id="new_password" name="new_password" required>

                            </div>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                    id="new_password_confirmation" name="new_password_confirmation" required>

                            </div>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-lock-reset me-1"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            function togglePasswordVisibility(inputId, buttonId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);
                const icon = button.querySelector('i');

                button.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('mdi-eye');
                        icon.classList.add('mdi-eye-off');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('mdi-eye-off');
                        icon.classList.add('mdi-eye');
                    }
                });
            }

            togglePasswordVisibility('current_password', 'toggleCurrentPassword');
            togglePasswordVisibility('new_password', 'toggleNewPassword');
            togglePasswordVisibility('new_password_confirmation', 'toggleConfirmPassword');
        });
    </script>
@endsection
