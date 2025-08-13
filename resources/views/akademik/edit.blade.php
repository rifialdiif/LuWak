<!-- Modal Edit Data Akademik -->
<div class="modal fade" id="modalEditAkademik" tabindex="-1" aria-labelledby="modalEditAkademikLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0 flex-column align-items-start">
                <div class="d-flex align-items-center mb-1">
                    <h5 class="modal-title fw-bold mb-0" id="modalEditAkademikLabel" style="font-size:1.5rem;">Edit Data
                        Akademik</h5>
                </div>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('akademik.update', $mahasiswa->id_mahasiswa) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_mahasiswa" value="{{ $mahasiswa->id_mahasiswa }}">
                @if ($errors->any() && session('edit_modal'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error') && session('edit_modal'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="modal-body pt-0">
                    <!-- Data Per Semester -->
                    <div class="mb-4 mt-2">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-journal-bookmark me-2 fs-5"></i>
                            <span class="fw-bold fs-5">Data Per Semester</span>
                        </div>
                        <div class="row g-3">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="col-md-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-2"
                                                style="width:32px; height:32px; font-weight:600;">{{ $i }}
                                            </div>
                                            <span class="fw-bold fs-6">Semester {{ $i }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold mb-1">IPS <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" maxlength="4"
                                                class="form-control form-control-lg ips-input"
                                                name="ips_semester_{{ $i }}"
                                                value="{{ old('ips_semester_' . $i, $mahasiswa->riwayatAkademik->{'ips_semester_' . $i} ?? '') }}"
                                                placeholder="Masukkan nilai IPS {{ $i }}" required>
                                        </div>
                                        <div>
                                            <label class="form-label fw-semibold mb-1">Status <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg"
                                                name="status_semester_{{ $i }}" required>
                                                <option disabled
                                                    {{ old('status_semester_' . $i, $mahasiswa->riwayatAkademik->{'status_semester_' . $i} ?? '') == '' ? 'selected' : '' }}>
                                                    Pilih status</option>
                                                @foreach ($statusSemesterOptions as $option)
                                                    <option value="{{ $option }}"
                                                        {{ old('status_semester_' . $i, $mahasiswa->riwayatAkademik->{'status_semester_' . $i} ?? '') == $option ? 'selected' : '' }}>
                                                        {{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <hr class="my-4">
                    <!-- Data Keseluruhan -->
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-clipboard-data me-2 fs-5"></i>
                            <span class="fw-bold fs-5">Data Keseluruhan</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-journal-check me-2 text-success fs-5"></i>
                                        <span class="fw-bold text-success">Total SKS Lulus</span>
                                    </div>
                                    <label class="form-label fw-semibold mb-1">Jumlah SKS yang Lulus <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control form-control-lg"
                                        name="sks_lulus"
                                        value="{{ old('sks_lulus', $mahasiswa->riwayatAkademik->total_sks_ditempuh ?? '') }}"
                                        placeholder="120" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-x-circle-fill me-2 text-danger fs-5"></i>
                                        <span class="fw-bold text-danger">Total SKS Tidak Lulus</span>
                                    </div>
                                    <label class="form-label fw-semibold mb-1">Jumlah SKS yang Tidak Lulus <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control form-control-lg"
                                        name="sks_tidak_lulus"
                                        value="{{ old('sks_tidak_lulus', $mahasiswa->riwayatAkademik->total_sks_tidak_lulus ?? '') }}"
                                        placeholder="6" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-file-earmark-text me-2 text-primary fs-5"></i>
                                        <span class="fw-bold text-primary">File Pendukung</span>
                                    </div>
                                    <label class="form-label fw-semibold mb-1">Upload File Pendukung (Bukti IPS 1-4,
                                        Status Mahasiswa Semester 1-4, Transkrip) <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group mb-2">
                                        <input type="file" class="form-control" name="dokumen_pendukung"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </div>
                                    @if ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->dokumen_pendukung)
                                        <div class="form-text">
                                            File saat ini: <a
                                                href="{{ asset('storage/file_pendukung/' . $mahasiswa->riwayatAkademik->dokumen_pendukung) }}"
                                                target="_blank">{{ $mahasiswa->riwayatAkademik->dokumen_pendukung }}</a>
                                        </div>
                                    @endif
                                    <div class="form-text">Format: PDF, JPG, JPEG, PNG (Max: 5MB)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-4">
                    <button type="button" class="btn btn-danger rounded-3 px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="reset" class="btn btn-secondary rounded-3 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .modal-content {
            border-radius: 1.5rem;
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0.5rem;
            padding-top: 1.5rem;
            padding-left: 2rem;
            padding-right: 2rem;
            position: relative;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .modal-header .text-muted {
            font-size: 1rem;
            margin-left: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .modal-header .btn-close {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
        }

        .modal-body {
            padding-left: 2rem;
            padding-right: 2rem;
        }

        .modal-body .fw-bold.fs-4 {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
        }

        .form-control-lg,
        .form-select-lg {
            font-size: 1.1rem;
            border-radius: 0.75rem;
        }

        .input-group .form-control {
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .input-group .btn {
            border-radius: 0 0.75rem 0.75rem 0;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ips-input').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    let val = input.value.replace(/[^0-9]/g, ''); // hanya angka
                    if (val.length > 1) {
                        val = val.slice(0, 1) + '.' + val.slice(1, 3); // 3.45
                    }
                    input.value = val;
                });
            });
        });
    </script>
@endpush
