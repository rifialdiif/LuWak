@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Detail Riwayat Akademik</h4>
                <div>
                    <a href="{{ route('akademik.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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

    <!-- SECTION 1: IDENTITAS MAHASISWA -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card rounded-4 border-1 position-relative" id="identitas-mahasiswa-card" style="overflow:visible;">
                <div class="card-body p-4 pb-5 position-relative">
                    <!-- Judul & badge prediksi -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person me-2 fs-3"></i>
                            <span class="fw-bold fs-4">Identitas Mahasiswa</span>
                        </div>
                        <div>
                            <div class="bg-white bg-opacity-10 border border-success rounded-3 px-2 py-1 d-flex align-items-center"
                                style="min-width:140px;">
                                <div class="d-flex flex-column align-items-center justify-content-center me-2"
                                    style="min-width:24px;">
                                    <i class="bi bi-bullseye text-success" style="font-size:1.2rem;"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center" style="line-height:1;">
                                    <span class="fw-semibold text-success" style="font-size:0.85rem;">Tepat Waktu</span>
                                    <span class="text-success" style="font-size:0.75rem; opacity:0.7;">Akurasi: 85.7% | Est:
                                        2025-08</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-0">
                        <!-- FOTO, NAMA, NIM -->
                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-start">
                            <div class="mb-2" style="width:130px; height:130px;">
                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Foto Mahasiswa"
                                    class="img-fluid rounded-circle border"
                                    style="width:100%; height:100%; object-fit:cover; background:#f4f4f4;">
                            </div>
                            <div class="fw-bold fs-5 mt-2 text-center">{{ $mahasiswa->user->nama ?? '-' }}</div>
                            <div class="text-muted text-center">{{ $mahasiswa->user->nip_nim ?? '-' }}</div>
                        </div>
                        <!-- BIODATA UTAMA -->
                        <div class="col-md-9 ps-md-5 mt-4 mt-md-0">
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-start mb-2 mb-md-0">
                                    <div class="d-flex flex-column">
                                        <div class="label-row">
                                            <i class="bi bi-journal-code me-2"></i>
                                            <span class="fw-semibold">Program Studi</span>
                                        </div>
                                        <div class="text-muted value-row">{{ $mahasiswa->user->prodi->nama_prodi ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-start">
                                    <div class="d-flex flex-column">
                                        <div class="label-row">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            <span class="fw-semibold">Angkatan</span>
                                        </div>
                                        <div class="text-muted value-row">{{ $mahasiswa->angkatan->tahun_angkatan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-start">
                                    <div class="d-flex flex-column">
                                        <div class="label-row">
                                            <i class="bi bi-person-badge me-2"></i>
                                            <span class="fw-semibold">Dosen Pembimbing Akademik</span>
                                        </div>
                                        <div class="text-muted value-row">
                                            {{ $mahasiswa->dpa && $mahasiswa->dpa->dosenPembimbing ? $mahasiswa->dpa->dosenPembimbing->nama : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2" style="border-top:1px solid #eee; opacity:.7;" />
                            <!-- Kontak Orang Tua -->
                            <div class="row mb-2">
                                <div class="col-md-12 d-flex align-items-start">
                                    <div class="d-flex flex-column">
                                        <div class="label-row">
                                            <i class="bi bi-telephone me-2"></i>
                                            <span class="fw-semibold">Kontak Orang Tua</span>
                                        </div>
                                        <div class="text-muted value-row"><i class="bi bi-envelope me-1"></i> Email:
                                            orangtua.ahmad@gmail.com</div>
                                        <div class="text-muted value-row"><i class="bi bi-whatsapp me-1"></i> WhatsApp:
                                            +62812-3456-7890</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2" style="border-top:1px solid #eee; opacity:.7;" />
                            <!-- File Transkrip -->
                            <div class="row align-items-center">
                                <div class="col d-flex align-items-start">
                                    <div class="d-flex flex-column">
                                        <div class="label-row">
                                            <i class="bi bi-file-earmark-text me-2"></i>
                                            <span class="fw-semibold">File Transkrip</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 value-row">
                                            <span class="text-muted">transkrip_2021110001.pdf</span>
                                            <span class="badge bg-dark rounded-pill">Tervalidasi</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-outline-dark rounded-3 px-4">
                                        <i class="bi bi-file-earmark-text me-1"></i> Lihat File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION 1 -->

    <!-- Section 2: Riwayat Akademik -->
    <div class="row">
        <div class="col-12">
            <div class="card rounded-4 border-1" id="riwayat-akademik-card" style="overflow:visible;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <i class="bi bi-graph-up me-2 fs-4"></i>
                            <span class="fw-bold fs-4" style="margin-left:0;">Riwayat Akademik</span>
                        </div>
                        {{-- Tombol tambah data jika diperlukan --}}
                    </div>
                    <div class="empty-akademik-state text-center py-5">
                        <div class="mx-auto mb-4"
                            style="width:120px; height:120px; background:#f6f6f6; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-journal-bookmark fs-1" style="color:#888;"></i>
                        </div>
                        <div class="fw-bold fs-5 mb-2">Belum Ada Data Riwayat Akademik</div>
                        <div class="text-muted mb-4" style="max-width:500px; margin:auto;">
                            Data riwayat akademik mahasiswa belum tersedia. Silakan input data akademik terlebih dahulu.
                        </div>
                        <a href="#"
                            class="btn btn-primary rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2"
                            style="font-weight:500;">
                            <i class="bi bi-journal-bookmark"></i> Input Data Akademik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Inisialisasi tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        #identitas-mahasiswa-card .label-row {
            display: flex;
            align-items: center;
        }

        #identitas-mahasiswa-card .value-row {
            padding-left: 28px;
        }

        #riwayat-akademik-card {
            background: #fff;
            border: 1px solid #eee;
        }

        #riwayat-akademik-card .empty-akademik-state .btn-dark {
            background: #181818;
            border: none;
            font-size: 1rem;
            transition: background 0.2s;
        }

        #riwayat-akademik-card .empty-akademik-state .btn-dark:hover {
            background: #222;
        }

        #riwayat-akademik-card .empty-akademik-state .text-muted {
            color: #888 !important;
        }

        #riwayat-akademik-card .card-body {
            padding-left: 2rem !important;
        }

        #riwayat-akademik-card .d-flex.align-items-center>.fs-4 {
            margin-right: 0.5rem;
        }
    </style>
@endpush
