<div class="modal fade" id="modalCatatanTidakValid" tabindex="-1" aria-labelledby="modalCatatanTidakValidLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0 flex-column align-items-start">
                <h5 class="modal-title fw-bold mb-0" id="modalCatatanTidakValidLabel">Catatan Tidak Valid</h5>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('akademik.validasi', $mahasiswa->id_mahasiswa) }}" method="POST">
                @csrf
                <input type="hidden" name="aksi" value="tidak_valid">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan_validasi" class="form-label">Catatan Penolakan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan_validasi" name="catatan_validasi" rows="4" required
                            placeholder="Tuliskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
