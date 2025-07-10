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
                                {{ $mahasiswas->where('riwayatAkademik.status_validasi', 'invalid')->count() }}</h4>
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
                                <option value="">Semua Status</option>
                                <option value="valid">Valid</option>
                                <option value="pending">Pending</option>
                                <option value="invalid">Invalid</option>
                                <option value="belum-ada">Belum Ada</option>
                            </select>
                        </div>
                        <div class="col-md-8 text-end">
                            <button type="button" class="btn btn-success" id="exportBtn" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Export Data">
                                <i class="bi bi-download me-1"></i>Export
                            </button>
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
                                        <th>Status Validasi</th>
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
                                                    @if ($mahasiswa->riwayatAkademik->status_validasi === 'valid')
                                                        <span class="badge bg-success">Valid</span>
                                                    @elseif($mahasiswa->riwayatAkademik->status_validasi === 'pending')
                                                        <span class="badge bg-warning">Menunggu Validasi</span>
                                                    @elseif($mahasiswa->riwayatAkademik->status_validasi === 'invalid')
                                                        <span class="badge bg-danger">Invalid</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Ada</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Belum Ada</span>
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
                    $('#basic-datatable').DataTable().column(5).search('').draw();
                } else if (status === 'belum-ada') {
                    $('#basic-datatable').DataTable().column(5).search('Belum Ada').draw();
                } else {
                    $('#basic-datatable').DataTable().column(5).search(status).draw();
                }
            });

            // Export Button (Tampilan saja)
            $('#exportBtn').on('click', function() {
                var $btn = $(this);
                var originalText = $btn.html();

                // Show loading state
                $btn.html('<i class="bi bi-arrow-clockwise me-1"></i>Exporting...');
                $btn.prop('disabled', true);

                // Simulate export process
                setTimeout(function() {
                    // Restore button state
                    $btn.html(originalText);
                    $btn.prop('disabled', false);

                    // Show success message
                    $btn.removeClass('btn-primary').addClass('btn-outline-primary');
                    setTimeout(function() {
                        $btn.removeClass('btn-outline-primary').addClass('btn-primary');
                    }, 2000);

                    // Show alert (optional)
                    alert('Fitur export akan segera tersedia!');
                }, 1000);
            });
        });
    </script>
@endpush
