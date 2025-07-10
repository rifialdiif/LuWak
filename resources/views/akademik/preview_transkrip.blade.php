<!-- Modal Preview Transkrip -->
<div class="modal fade" id="modalPreviewTranskrip" tabindex="-1" aria-labelledby="modalPreviewTranskripLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0 flex-column align-items-start">
                <div class="d-flex align-items-center mb-1">
                    <h5 class="modal-title fw-bold mb-0" id="modalPreviewTranskripLabel" style="font-size:1.3rem;">
                        Preview File Transkrip
                    </h5>
                </div>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                @if ($mahasiswa->riwayatAkademik && $mahasiswa->riwayatAkademik->dokumen_pendukung)
                    @php
                        $file = $mahasiswa->riwayatAkademik->dokumen_pendukung;
                        $fileExt = pathinfo($file, PATHINFO_EXTENSION);
                        $fileUrl = asset('storage/file_pendukung/' . $file);
                        $statusValidasi = $mahasiswa->riwayatAkademik->status_validasi;
                        $isMahasiswa = Auth::user()->role === 'mahasiswa';
                        $validasiBy = $mahasiswa->riwayatAkademik->validasi_by;
                        $validasiAt = $mahasiswa->riwayatAkademik->validasi_at;
                    @endphp
                    <div class="text-center mb-4">
                        @if (in_array(strtolower($fileExt), ['pdf']))
                            <iframe src="{{ $fileUrl }}" width="100%" height="600px"
                                style="border-radius:1rem; border:1px solid #eee;"></iframe>
                        @else
                            <img src="{{ $fileUrl }}" alt="Transkrip" class="img-fluid rounded-3"
                                style="max-height:600px;">
                        @endif
                    </div>
                    @if ($statusValidasi === 'valid')
                        <div class="alert alert-success mt-4">
                            <div class="fw-semibold mb-1">Divalidasi Oleh: <span
                                    class="text-primary">{{ $mahasiswa->riwayatAkademik->validator->nama ?? '-' }}</span>
                            </div>
                            <div class="mb-0">Waktu: <span
                                    class="text-primary">{{ $validasiAt ? \Carbon\Carbon::parse($validasiAt)->format('d-m-Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($statusValidasi === 'tidak valid')
                        <div class="alert alert-danger mt-4">
                            <div class="fw-semibold mb-1">Ditolak Oleh: <span
                                    class="text-primary">{{ $mahasiswa->riwayatAkademik->validator->nama ?? '-' }}</span>
                            </div>
                            <div class="mb-0">Waktu: <span
                                    class="text-primary">{{ $validasiAt ? \Carbon\Carbon::parse($validasiAt)->format('d-m-Y H:i') : '-' }}</span>
                            </div>
                            @if ($mahasiswa->riwayatAkademik->catatan_validasi)
                                <div class="border-top my-2"></div>
                                <div><b>Catatan Penolakan:</b><br>{{ $mahasiswa->riwayatAkademik->catatan_validasi }}
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (!$isMahasiswa)
                        @if ($statusValidasi !== 'valid')
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <!-- Tombol Validasi Tidak Valid: buka modal -->
                                <button type="button" class="btn btn-danger px-4" data-bs-toggle="modal"
                                    data-bs-target="#modalCatatanTidakValid">Tidak Valid</button>
                                <!-- Tombol Validasi Valid -->
                                <form action="{{ route('akademik.validasi', $mahasiswa->id_mahasiswa) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="valid">
                                    <button type="submit" class="btn btn-success px-4">Valid</button>
                                </form>
                            </div>
                            @include('akademik.modal_catatan_tidak_valid')
                        @endif
                    @endif
                @else
                    <div class="text-center text-muted py-5">File pendukung tidak tersedia.</div>
                @endif
            </div>
        </div>
    </div>
</div>
