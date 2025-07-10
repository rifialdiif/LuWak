@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4" style="min-height:100vh;">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Deteksi Kelulusan</h4>
                    <div>
                        <a href="{{ route('prediksi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <ul class="nav nav-tabs nav-justified bg-white rounded-3 shadow-sm" style="overflow:hidden;">
                <li class="nav-item">
                    <a class="nav-link active fw-semibold" id="tab-prediksi" data-bs-toggle="tab"
                        href="#prediksi">Prediksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" id="tab-riwayat" data-bs-toggle="tab" href="#riwayat">Riwayat
                        Prediksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" id="tab-intervensi" data-bs-toggle="tab" href="#intervensi">Riwayat
                        Intervensi</a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="prediksi">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                            <div class="mb-2 d-flex align-items-center">
                                <i class="bi bi-person fs-4 me-2"></i>
                                <span class="fw-bold fs-5">Data Mahasiswa</span>
                            </div>
                            <div class="text-muted mb-3" style="font-size:1rem;">Data lengkap mahasiswa untuk analisis
                                prediksi</div>
                            <div class="mb-3">
                                <div class="fw-semibold mb-1">Identitas Mahasiswa</div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label mb-0">NIM</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $mahasiswa->user->nip_nim ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-0">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $mahasiswa->user->nama ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label mb-0">Program Studi</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $mahasiswa->user->prodi->nama_prodi ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mb-0">Angkatan</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $mahasiswa->angkatan->tahun_angkatan ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-0">Dosen Pembimbing Akademik (DPA)</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $mahasiswa->dpa && $mahasiswa->dpa->dosenPembimbing ? $mahasiswa->dpa->dosenPembimbing->nama : '-' }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="fw-semibold mb-1">Riwayat Akademik</div>
                            <div class="mb-2">Indeks Prestasi Semester (IPS)</div>
                            <div class="row g-2">
                                <div class="col">
                                    <label class="form-label mb-1">Semester 1</label>
                                    <input type="text" class="form-control bg-light text-center"
                                        value="{{ $mahasiswa->riwayatAkademik->ips_semester_1 ?? '-' }}" readonly>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1">Semester 2</label>
                                    <input type="text" class="form-control bg-light text-center"
                                        value="{{ $mahasiswa->riwayatAkademik->ips_semester_2 ?? '-' }}" readonly>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1">Semester 3</label>
                                    <input type="text" class="form-control bg-light text-center"
                                        value="{{ $mahasiswa->riwayatAkademik->ips_semester_3 ?? '-' }}" readonly>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1">Semester 4</label>
                                    <input type="text" class="form-control bg-light text-center"
                                        value="{{ $mahasiswa->riwayatAkademik->ips_semester_4 ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="mb-2 mt-2">Status Mahasiswa per Semester</div>
                            <div class="row g-2 mb-3">
                                @php
                                    $status = [
                                        1 => $mahasiswa->riwayatAkademik->status_semester_1 ?? '-',
                                        2 => $mahasiswa->riwayatAkademik->status_semester_2 ?? '-',
                                        3 => $mahasiswa->riwayatAkademik->status_semester_3 ?? '-',
                                        4 => $mahasiswa->riwayatAkademik->status_semester_4 ?? '-',
                                    ];
                                    $statusColor = [
                                        'AKTIF' => 'border-success bg-light bg-opacity-10 text-success',
                                        'CUTI' => 'border-warning bg-light bg-opacity-10 text-warning',
                                        'NON-AKTIF' => 'border-secondary bg-light bg-opacity-10 text-secondary',
                                        'default' => 'border-light bg-light text-secondary',
                                    ];
                                @endphp
                                @foreach ($status as $i => $val)
                                    <div class="col">
                                        <label class="form-label mb-1">Semester {{ $i }}
                                        </label>
                                        <div class="rounded-3 border d-flex align-items-center justify-content-center py-2 fw-bold text-uppercase {{ $statusColor[strtoupper($val)] ?? $statusColor['default'] }}"
                                            style="min-height:48px;">
                                            {{ $val }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row g-3 mt-2 mb-3">
                                <div class="col-md-6 col-lg-6">
                                    <div class="text-center p-3 rounded-3" style="background:#f4f8ff;">
                                        <div class="fw-bold display-6 text-primary mb-0">
                                            {{ $mahasiswa->riwayatAkademik->total_sks_lulus ?? 0 }}</div>
                                        <div class="text-primary">SKS</div>
                                        <div class="small text-muted">Total SKS Ditempuh</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="text-center p-3 rounded-3" style="background:#fff4f4;">
                                        <div class="fw-bold display-6 text-danger mb-0">
                                            {{ $mahasiswa->riwayatAkademik->total_sks_tidak_lulus ?? 0 }}</div>
                                        <div class="text-danger">SKS</div>
                                        <div class="small text-muted">SKS Tidak Lulus</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button
                                    class="btn btn-primary w-100 py-2 rounded-3 fw-bold fs-6 d-flex align-items-center justify-content-center"
                                    style="gap:0.5rem;">
                                    <i class="bi bi-journal-bookmark fs-5"></i>
                                    Prediksi Kelulusan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div
                            class="bg-white rounded-4 shadow-sm p-4 h-100 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="text-secondary">
                                <i class="bi bi-journal-bookmark fs-1"></i>
                                <div class="mt-2">Belum ada hasil prediksi<br><span class="text-muted">Klik tombol
                                        prediksi untuk memulai analisis</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="riwayat">
                @include('prediksi.riwayatprediksi')
            </div>
            <div class="tab-pane fade" id="intervensi">
                @include('prediksi.riwayatintervensi')
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link.active {
            background: #f5f8ff;
            border-bottom: 2px solid #0d6efd;
            color: #0d6efd;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #888;
        }
    </style>
@endpush
