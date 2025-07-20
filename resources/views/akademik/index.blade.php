@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
        </div>
    </div>
    {{-- @if (session('success'))
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
    @endif --}}
    <!-- Statistik Ringkasan -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mahasiswas->where('riwayatAkademik.status_validasi', 'valid')->count() }}
                            </h4>
                            <p class="mb-0">Valid</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-check-circle font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                {{ $mahasiswas->where('riwayatAkademik.status_validasi', 'pending')->count() }}</h4>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-clock font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                {{ $mahasiswas->where('riwayatAkademik.status_validasi', 'tidak valid')->count() }}</h4>
                            <p class="mb-0">Invalid</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-close-circle font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mahasiswas->whereNull('riwayatAkademik')->count() }}</h4>
                            <p class="mb-0">Belum Ada</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-help-circle font-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title header-title mb-0">Data {{ $title }}</h4>
                        <div class="text-muted">
                            <small>
                                Role: <span class="badge bg-primary">{{ Auth::user()->role }}</span> |
                                Total: <span class="badge bg-info">{{ $mahasiswas->count() }}</span> mahasiswa
                            </small>
                        </div>
                    </div>

                    <!-- Filter dan Export -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Riwayat Akademik/Status Validasi</option>
                                <option value="sudah-diisi">Sudah Diisi</option>
                                <option value="belum-diisi">Belum Diisi</option>
                                <option value="valid">Valid</option>
                                <option value="pending">Pending</option>
                                <option value="invalid">Invalid</option>
                            </select>
                        </div>
                        <div class="col-md-8 text-end">
                            @if (Auth::user()->role === 'admin')
                                <button type="button" class="btn btn-success" id="exportBtn" data-bs-placement="top"
                                    title="Export Data" data-bs-toggle="modal" data-bs-target="#exportModal">
                                    <i class="bi bi-download me-1"></i>Export
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="multi-item-preview">
                            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Angkatan</th>
                                        <th>Isi Riwayat Akademik?</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr>
                                            <td>{{ $mahasiswa->user->nip_nim ?? '-' }}</td>
                                            <td>{{ $mahasiswa->user->nama ?? '-' }}</td>
                                            <td>{{ $mahasiswa->user->prodi->nama_prodi ?? '-' }}</td>
                                            <td>{{ $mahasiswa->angkatan->tahun_angkatan ?? '-' }}</td>
                                            <td>
                                                @if ($mahasiswa->riwayatAkademik)
                                                    <div>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Sudah Diisi
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            Status:
                                                            @if ($mahasiswa->riwayatAkademik->status_validasi === 'valid')
                                                                <span class="badge bg-success">Valid</span>
                                                            @elseif($mahasiswa->riwayatAkademik->status_validasi === 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($mahasiswa->riwayatAkademik->status_validasi === 'tidak valid')
                                                                <span class="badge bg-danger">Invalid</span>
                                                            @else
                                                                <span class="badge bg-secondary">-</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle me-1"></i>Belum Diisi
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('akademik.show', $mahasiswa->id_mahasiswa) }}"
                                                    class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Lihat Detail">
                                                    <i class="bi bi-eye me-1"></i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    @include('akademik.preview_transkrip')
    @include('akademik.export')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Inisialisasi tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Fungsi filter status
            $('#statusFilter').on('change', function() {
                var status = $(this).val();

                if (status === '') {
                    $('#basic-datatable').DataTable().column(4).search('').draw();
                } else if (status === 'belum-diisi') {
                    $('#basic-datatable').DataTable().column(4).search('Belum Diisi').draw();
                } else if (status === 'sudah-diisi') {
                    $('#basic-datatable').DataTable().column(4).search('Sudah Diisi').draw();
                } else if (status === 'valid') {
                    $('#basic-datatable').DataTable().column(4).search('Valid').draw();
                } else if (status === 'pending') {
                    $('#basic-datatable').DataTable().column(4).search('Pending').draw();
                } else if (status === 'invalid') {
                    $('#basic-datatable').DataTable().column(4).search('Invalid').draw();
                }
            });

            // Load data angkatan saat modal export dibuka
            $('#exportModal').on('show.bs.modal', function() {
                loadAngkatanList();
                // Reset form dan info
                $('#exportForm')[0].reset();
                $('#exportInfo').hide();
                $('#emptyDataAlert').hide();
                $('#exportSubmitBtn').prop('disabled', false);
            });

            // Fungsi untuk mengambil data angkatan
            function loadAngkatanList() {
                $.ajax({
                    url: '{{ route('akademik.angkatanList') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var select = $('#angkatan_export');
                        select.empty();
                        select.append('<option value="">-- Pilih Angkatan --</option>');

                        data.forEach(function(angkatan) {
                            select.append('<option value="' + angkatan.id_angkatan + '">' +
                                angkatan.tahun_angkatan + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading angkatan:', error);
                        alert('Gagal memuat data angkatan. Silakan coba lagi.');
                    }
                });
            }

            // Event handler untuk perubahan angkatan
            $('#angkatan_export').on('change', function() {
                var selectedAngkatan = $(this).val();

                if (selectedAngkatan) {
                    loadExportInfo(selectedAngkatan);
                } else {
                    $('#exportInfo').hide();
                    $('#emptyDataAlert').hide();
                    $('#exportSubmitBtn').prop('disabled', false);
                }
            });

            // Fungsi untuk mengambil informasi export
            function loadExportInfo(angkatanId) {
                $.ajax({
                    url: '{{ route('akademik.exportInfo') }}',
                    type: 'POST',
                    data: {
                        angkatan: angkatanId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;

                            if (data.total_mahasiswa === 0) {
                                // Data kosong
                                $('#emptyDataAlert').show();
                                $('#emptyDataMessage').text('Tidak ada mahasiswa di angkatan ' + data
                                    .angkatan);
                                $('#exportInfo').hide();
                                $('#exportSubmitBtn').prop('disabled', true);
                            } else {
                                // Ada data
                                $('#emptyDataAlert').hide();
                                $('#exportInfo').show();
                                $('#exportSubmitBtn').prop('disabled', false);

                                var fileInfoHtml = `
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Angkatan:</strong> ${data.angkatan}<br>
                                            <strong>Total Mahasiswa:</strong> ${data.total_mahasiswa}<br>
                                            <strong>Dengan Riwayat:</strong> ${data.mahasiswa_with_riwayat}<br>
                                            <strong>Tanpa Riwayat:</strong> ${data.mahasiswa_without_riwayat}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Estimasi Ukuran:</strong> ${data.file_size_estimate}<br>
                                            <strong>Format:</strong> CSV (UTF-8)
                                        </div>
                                    </div>
                                `;
                                $('#fileInfo').html(fileInfoHtml);
                            }
                        } else {
                            $('#emptyDataAlert').show();
                            $('#emptyDataMessage').text(response.message ||
                                'Terjadi kesalahan saat mengambil informasi.');
                            $('#exportInfo').hide();
                            $('#exportSubmitBtn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading export info:', error);
                        $('#emptyDataAlert').show();
                        $('#emptyDataMessage').text(
                            'Gagal memuat informasi export. Silakan coba lagi.');
                        $('#exportInfo').hide();
                        $('#exportSubmitBtn').prop('disabled', true);
                    }
                });
            }

            // Handle form submission untuk validasi data kosong
            $('#exportForm').on('submit', function(e) {
                var selectedAngkatan = $('#angkatan_export').val();

                if (!selectedAngkatan) {
                    e.preventDefault();
                    alert('Silakan pilih angkatan terlebih dahulu.');
                    return false;
                }

                // Cek apakah ada data kosong
                if ($('#emptyDataAlert').is(':visible')) {
                    e.preventDefault();
                    alert('Tidak ada data untuk diexport. Silakan pilih angkatan lain.');
                    return false;
                }
            });

        });
    </script>
@endpush
