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

    @if (Auth::user()->role === 'mahasiswa' &&
            $mahasiswa->riwayatAkademik &&
            $mahasiswa->riwayatAkademik->status_validasi === 'tidak valid')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            File transkrip Anda <b>tidak valid</b>. Silakan upload ulang file yang benar sesuai ketentuan.
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
                            <div class="bg-light bg-opacity-10 border border-success rounded-3 px-2 py-1 d-flex align-items-center"
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
                                            {{ $mahasiswa->email_ortu ?? '-' }}
                                        </div>
                                        <div class="text-muted value-row"><i class="bi bi-whatsapp me-1"></i> WhatsApp:
                                            {{ $mahasiswa->no_hp_orang_tua ?? '-' }}
                                        </div>
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
                                            <span class="text-muted">
                                                @if ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->dokumen_transkrip)
                                                    Transkrip_{{ $mahasiswa->user->nip_nim ?? 'unknown' }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                            <span class="badge bg-dark rounded-pill">
                                                {{ $mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->status_validasi === 'valid' ? 'Tervalidasi' : ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->status_validasi === 'pending' ? 'Menunggu Validasi' : ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->status_validasi === 'tidak valid' ? 'Tidak Valid' : '-')) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    @if ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->dokumen_transkrip)
                                        <button type="button" class="btn btn-outline-dark rounded-3 px-4"
                                            data-bs-toggle="modal" data-bs-target="#modalPreviewTranskrip">
                                            <i class="bi bi-file-earmark-text me-1"></i> Lihat File
                                        </button>
                                    @endif
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
    <div class="row" style="margin-top:-40px;">
        <div class="col-12">
            <div class="card rounded-4 border-1" id="riwayat-akademik-card" style="overflow:visible;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <i class="bi bi-graph-up me-2 fs-4"></i>
                            <span class="fw-bold fs-4" style="margin-left:0;">Riwayat Akademik</span>
                        </div>
                        @if ($mahasiswa->riwayatAkademik)
                            <div>
                                <a href="#" class="btn btn-outline-primary me-2 btn-edit-akademik"
                                    data-id="{{ $mahasiswa->id_mahasiswa }}"
                                    data-ips1="{{ $mahasiswa->riwayatAkademik->ips_semester_1 }}"
                                    data-ips2="{{ $mahasiswa->riwayatAkademik->ips_semester_2 }}"
                                    data-ips3="{{ $mahasiswa->riwayatAkademik->ips_semester_3 }}"
                                    data-ips4="{{ $mahasiswa->riwayatAkademik->ips_semester_4 }}"
                                    data-status1="{{ $mahasiswa->riwayatAkademik->status_semester_1 }}"
                                    data-status2="{{ $mahasiswa->riwayatAkademik->status_semester_2 }}"
                                    data-status3="{{ $mahasiswa->riwayatAkademik->status_semester_3 }}"
                                    data-status4="{{ $mahasiswa->riwayatAkademik->status_semester_4 }}"
                                    data-skslulus="{{ $mahasiswa->riwayatAkademik->total_sks_lulus }}"
                                    data-skstdklulus="{{ $mahasiswa->riwayatAkademik->total_sks_tidak_lulus }}"
                                    data-file="{{ $mahasiswa->riwayatAkademik->dokumen_transkrip }}"
                                    data-bs-toggle="modal" data-bs-target="#modalEditAkademik">
                                    <i class="bi bi-pencil-square me-1"></i>Edit Data
                                </a>
                                <form id="form-delete-{{ $mahasiswa->id_mahasiswa }}"
                                    action="{{ route('akademik.destroy', $mahasiswa->id_mahasiswa) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete"
                                        data-id="{{ $mahasiswa->id_mahasiswa }}">
                                        <i class="bi bi-trash me-1"></i>Hapus Data
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    @if ($mahasiswa->riwayatAkademik)
                        {{-- Summary --}}
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100 d-flex align-items-center gap-3">
                                    <i class="bi bi-journal-check fs-2 text-success"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:1rem;">Total SKS</div>
                                        <div class="fw-bold text-success" style="font-size:2rem;">
                                            {{ $mahasiswa->riwayatAkademik->total_sks_lulus }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100 d-flex align-items-center gap-3">
                                    <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:1rem;">SKS Tidak Lulus</div>
                                        <div class="fw-bold text-danger" style="font-size:2rem;">
                                            {{ $mahasiswa->riwayatAkademik->total_sks_tidak_lulus }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100 d-flex align-items-center gap-3">
                                    <i class="bi bi-calendar2-week fs-2 text-purple"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:1rem;">Semester</div>
                                        <div class="fw-bold" style="font-size:2rem; color:#a259ff;">4</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table align-middle table-bordered table-hover" style="font-size:0.88rem;">
                                <thead class="table-light">
                                    <tr class="text-center align-middle">
                                        <th style="width: 80px;">Semester</th>
                                        <th style="width: 100px;">IPS</th>
                                        <th style="width: 160px;">Status Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <tr class="text-center align-middle">
                                            <td class="fw-bold">{{ $i }}</td>
                                            <td>
                                                <span class="badge px-3 py-2"
                                                    style="background:#fff; color:#222; font-size:0.84rem; font-weight:700; border:1.5px solid #e0e0e0; border-radius:1.5rem; box-shadow:none; letter-spacing:0.5px;">
                                                    {{ number_format($mahasiswa->riwayatAkademik->{'ips_semester_' . $i}, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge px-2 py-1"
                                                    style="background:#f3f4f6; color:#333; font-size:0.85rem; font-weight:500; border-radius:0.6rem;">
                                                    {{ ucfirst($mahasiswa->riwayatAkademik->{'status_semester_' . $i}) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Empty state --}}
                        <div class="empty-akademik-state text-center py-5">
                            <div class="mx-auto mb-4"
                                style="width:120px; height:120px; background:#f6f6f6; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-journal-bookmark fs-1" style="color:#888;"></i>
                            </div>
                            <div class="fw-bold fs-5 mb-2">Belum Ada Data Riwayat Akademik</div>
                            <div class="text-muted mb-4" style="max-width:500px; margin:auto;">
                                Data riwayat akademik mahasiswa belum tersedia. Silakan input data akademik terlebih dahulu.
                            </div>
                            <button type="button"
                                class="btn btn-primary rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2"
                                style="font-weight:500;" data-bs-toggle="modal" data-bs-target="#modalInputAkademik">
                                <i class="bi bi-journal-bookmark"></i> Input Data Akademik
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('akademik.create')
    @include('akademik.edit')
    @include('akademik.preview_transkrip')
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handler tombol edit
            document.querySelectorAll('.btn-edit-akademik').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    // Set action form
                    var id = btn.dataset.id;
                    var form = document.getElementById('formEditAkademik');
                    form.action = "{{ url('/akademik') }}/" + id + "/update";

                    // Set value input
                    document.getElementById('edit_id_mahasiswa').value = id;
                    for (let i = 1; i <= 4; i++) {
                        document.getElementById('edit_ips_semester_' + i).value = btn.dataset[
                            'ips' + i] || '';
                        document.getElementById('edit_status_semester_' + i).value = btn.dataset[
                            'status' + i] || '';
                    }
                    document.getElementById('edit_sks_lulus').value = btn.dataset.skslulus || '';
                    document.getElementById('edit_sks_tidak_lulus').value = btn.dataset
                        .skstdklulus || '';

                    // File info
                    let fileInfo = '';
                    if (btn.dataset.file) {
                        fileInfo = 'File saat ini: <a href=\"{{ asset('storage/transkrip') }}/' +
                            btn.dataset.file + '\" target=\"_blank\">' + btn.dataset.file + '</a>';
                    }
                    document.getElementById('edit_file_info').innerHTML = fileInfo;
                });
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "Yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-' + id).submit();
                }
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

        #riwayat-akademik-card {}

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

        .table-bordered th,
        .table-bordered td {}

        .table-hover tbody tr:hover {
            background: #f6faff !important;
        }
    </style>
@endpush
