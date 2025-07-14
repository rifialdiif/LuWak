@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4" style="min-height:100vh;">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Deteksi Kelulusan</h4>
                    <div>
                        @if (Auth::user()->role !== 'mahasiswa')
                            <a href="{{ route('prediksi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        @endif
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
                                <div class="mb-2 row align-items-center">
                                    <div class="col-auto">
                                        <label class="form-label mb-0">Dokumen Pendukung</label>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($mahasiswa->riwayatAkademik->dokumen_pendukung)
                                                <a href="{{ $mahasiswa->riwayatAkademik->dokumen_pendukung }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalPreviewTranskrip"
                                                    class="text-primary text-decoration-underline">Lihat
                                                    Dokumen</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            @php
                                                $status = strtolower(
                                                    $mahasiswa->riwayatAkademik->status_validasi ?? '',
                                                );
                                                $badgeClass = 'bg-secondary';
                                                if ($status === 'valid') {
                                                    $badgeClass = 'bg-success';
                                                } elseif ($status === 'tidak valid') {
                                                    $badgeClass = 'bg-danger';
                                                } elseif ($status === 'menunggu') {
                                                    $badgeClass = 'bg-warning text-dark';
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }}">{{ $mahasiswa->riwayatAkademik->status_validasi ?? '-' }}</span>
                                        </div>
                                    </div>
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
                                            {{ $mahasiswa->riwayatAkademik->total_sks_ditempuh ?? 0 }}</div>
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
                                <form id="formPrediksi"
                                    action="{{ route('prediksi.predict', $mahasiswa->id_mahasiswa) }}" method="POST">
                                    @csrf
                                    <button id="btnPrediksi"
                                        class="btn btn-primary w-100 py-2 rounded-3 fw-bold fs-6 d-flex align-items-center justify-content-center"
                                        style="gap:0.5rem;" type="submit">
                                        <span id="btnText"><i class="bi bi-journal-bookmark fs-5"></i> Prediksi
                                            Kelulusan</span>
                                        <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div id="hasilPrediksiSection"
                            class="bg-white rounded-4 shadow-sm p-4 h-100 d-flex flex-column align-items-center justify-content-center text-center">
                            <div id="hasilPrediksiContent">
                                <div class="text-secondary">
                                    <i class="bi bi-journal-bookmark fs-1"></i>
                                    <div class="mt-2">Belum ada hasil prediksi<br><span class="text-muted">Klik tombol
                                            prediksi untuk memulai analisis</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="riwayat">
                <div id="riwayatPrediksiWrapper">
                    @include('prediksi.riwayatprediksi')
                </div>
            </div>
            <div class="tab-pane fade" id="intervensi">
                @include('prediksi.riwayatintervensi')
            </div>
        </div>
    </div>
    @include('prediksi.form_intervensi')
    @include('akademik.preview_transkrip')
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

        .badge.bg-danger.bg-opacity-10 {
            background: #f8d7da !important;
            color: #b02a37 !important;
        }

        .badge.bg-success.bg-opacity-10 {
            background: #d1e7dd !important;
            color: #146c43 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formPrediksi');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = form.action;
                const btn = document.getElementById('btnPrediksi');
                const spinner = document.getElementById('spinner');
                const hasilSection = document.getElementById('hasilPrediksiContent');

                // Show spinner
                spinner.classList.remove('d-none');
                btn.setAttribute('disabled', true);

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                            'Accept': 'application/json'
                        },
                    })
                    .then(async response => {
                        spinner.classList.add('d-none');
                        btn.removeAttribute('disabled');
                        let data;
                        try {
                            data = await response.json();
                        } catch {
                            data = null;
                        }
                        // Jika error 429 (batas harian)
                        if (response.status === 429) {
                            const notif = document.createElement('div');
                            notif.className = 'alert alert-warning fw-bold';
                            notif.innerText = (data && data.message) ? data.message :
                                'Batas prediksi harian tercapai. Silakan coba lagi besok.';
                            // Tampilkan notifikasi di atas hasil prediksi
                            const hasilSection = document.getElementById(
                                'hasilPrediksiSection');
                            hasilSection.prepend(notif);
                            return;
                        }
                        if (!response.ok) {
                            throw new Error((data && data.message) ? data.message :
                                'Terjadi kesalahan');
                        }
                        return data;
                    })
                    .then(res => {
                        if (res && res.success && res.data) {
                            const hasil = res.data;
                            const confidence = Math.round((hasil.confidence_score ?? 0) * 1000) /
                                10; // 1 desimal
                            let badge, icon, badgeClass;
                            if (hasil.prediction == 1) {
                                badge = 'Beresiko Tidak Lulus Tepat Waktu';
                                icon = 'bi-exclamation-triangle-fill';
                                badgeClass = 'bg-danger bg-opacity-10 text-danger border-0';
                            } else {
                                badge = 'Lulus Tepat Waktu';
                                icon = 'bi-check-circle-fill';
                                badgeClass = 'bg-success bg-opacity-10 text-success border-0';
                            }
                            let faktorTitle = hasil.prediction == 1 ? 'Faktor Risiko' :
                                'Faktor Pendukung';
                            hasilSection.innerHTML = `
                                <div class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-4 me-2"></i>
                                    <span class="fw-bold fs-5">Hasil Prediksi</span>
                                </div>
                                <div class="text-muted mb-3" style="font-size:1rem;">Hasil analisis prediksi kelulusan</div>
                                <div class="mb-3 text-center">
                                    <span class="badge rounded-pill px-4 py-2 d-inline-flex align-items-center ${badgeClass}" style="font-size:1.1rem;">
                                        <i class="bi ${icon} me-2"></i> ${badge}
                                    </span>
                                </div>
                                <div class="fw-bold text-center" style="font-size:2rem;">${confidence}% Confidence Score</div>
                                <hr>
                                <div class="mb-2 text-start">
                                    <div class="fw-bold mb-2">${faktorTitle}</div>
                                    <div>
                                        ${(hasil.faktor_risiko ?? []).map(f => `<span class="badge rounded-pill border border-secondary text-dark me-2 mb-1" style="background:#fff;">${f}</span>`).join('')}
                                    </div>
                                </div>
                                <hr>
                                <div class="mt-3">
                                @if (Auth::user()->role !== 'mahasiswa')
                                    <button
                                        class="btn btn-outline-primary w-100 py-2 fw-bold"
                                        type="button"
                                        id="btnKirimIntervensi"
                                        data-nama-mahasiswa="{{ $mahasiswa->user->nama ?? '' }}"
                                        data-email-ortu="{{ $mahasiswa->email_ortu ?? '' }}"
                                        data-hp-ortu="{{ $mahasiswa->no_hp_orang_tua ?? '' }}"
                                    >
                                        <i class="bi bi-bell me-2"></i> Kirim Notifikasi Intervensi
                                    </button>
                                </div>
                                @endif
                            `;
                            // Hapus class center agar layout berubah
                            const section = document.getElementById('hasilPrediksiSection');
                            section.classList.remove('d-flex', 'flex-column', 'align-items-center',
                                'justify-content-center', 'text-center');
                            // Update riwayat prediksi tanpa reload
                            if (res.riwayat_html) {
                                const riwayatWrapper = document.getElementById(
                                    'riwayatPrediksiWrapper');
                                if (riwayatWrapper) riwayatWrapper.innerHTML = res.riwayat_html;
                            }
                        } else {
                            hasilSection.innerHTML =
                                `<div class="alert alert-danger">Gagal mendapatkan hasil prediksi.</div>`;
                        }
                    })
                    .catch(err => {
                        hasilSection.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
                    });
            });

            // Handler tombol intervensi
            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'btnKirimIntervensi') {
                    // Ambil data dari atribut tombol
                    const namaMahasiswa = e.target.getAttribute('data-nama-mahasiswa') || '';
                    const emailOrtu = e.target.getAttribute('data-email-ortu') || '';
                    const hpOrtu = e.target.getAttribute('data-hp-ortu') || '';

                    // Prefill form
                    document.getElementById('penerima').value = 'Orang Tua/Wali ' + namaMahasiswa;
                    // Default: tampilkan nomor HP ortu
                    document.getElementById('nomor_hp').value = hpOrtu;
                    document.getElementById('metodeSms').checked = true;

                    // Toggle input sesuai metode
                    document.getElementById('metodeEmail').addEventListener('change', function() {
                        document.getElementById('nomor_hp').value = emailOrtu;
                    });
                    document.getElementById('metodeSms').addEventListener('change', function() {
                        document.getElementById('nomor_hp').value = hpOrtu;
                    });

                    // Tampilkan modal (Bootstrap 5)
                    var modal = new bootstrap.Modal(document.getElementById('modalIntervensi'));
                    modal.show();
                }
            });
        });
    </script>
@endpush
