@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Laporan Prediksi Kelulusan</h4>
                </div>
            </div>
        </div> --}}

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

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h5 class="card-title mb-2">Cetak Laporan Prediksi Kelulusan</h5>
                            <p class="text-muted mb-0">Pilih filter untuk menghasilkan laporan PDF yang berisi daftar
                                mahasiswa beserta hasil prediksi kelulusan</p>
                        </div>

                        <form action="{{ route('laporan.generatePdf') }}" method="POST" id="laporanForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="angkatan_id" class="form-label">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Angkatan
                                    </label>
                                    <select class="form-select select2" id="angkatan_id" name="angkatan_id"
                                        data-placeholder="Pilih Angkatan (Opsional)">
                                        <option value=""></option>
                                        @foreach ($angkatans as $angkatan)
                                            <option value="{{ $angkatan->id_angkatan }}"
                                                {{ old('angkatan_id') == $angkatan->id_angkatan ? 'selected' : '' }}>
                                                {{ $angkatan->tahun_angkatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Kosongkan untuk semua angkatan</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="prodi_id" class="form-label">
                                        <i class="bi bi-book me-1"></i>
                                        Program Studi
                                    </label>
                                    <select class="form-select select2" id="prodi_id" name="prodi_id"
                                        data-placeholder="Pilih Program Studi (Opsional)">
                                        <option value=""></option>
                                        @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->id_prodi }}"
                                                {{ old('prodi_id') == $prodi->id_prodi ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Kosongkan untuk semua program studi</div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="bi bi-arrow-clockwise me-1"></i>
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="btnGenerate">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>
                                            Generate PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4">
                            <div class="alert alert-info border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <strong>Informasi:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li>Laporan akan berisi daftar mahasiswa beserta NIM, hasil prediksi, dan
                                                confidence score</li>
                                            <li>Jika tidak memilih filter, akan generate semua data yang tersedia</li>
                                            <li>Judul laporan akan menyesuaikan dengan filter yang dipilih</li>
                                            <li>File PDF akan otomatis terdownload setelah proses selesai</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // // Form submission
            // $('#laporanForm').on('submit', function(e) {
            //     e.preventDefault();

            //     // Show loading state
            //     $('#btnGenerate').prop('disabled', true).html(
            //         '<i class="bi bi-hourglass-split me-1"></i> Generating...');

            //     // Submit form
            //     this.submit();
            // });
        });

        function resetForm() {
            $('#angkatan_id').val('').trigger('change');
            $('#prodi_id').val('').trigger('change');
        }
    </script>
@endpush
