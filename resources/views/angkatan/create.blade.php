<div class="modal fade" id="angkatanModal" tabindex="-1" aria-labelledby="angkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('angkatan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="angkatanModalLabel">Tambah Angkatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tahun_angkatan" class="form-label">Tahun Angkatan</label>
                        <input type="number" class="form-control" id="tahun_angkatan" name="tahun_angkatan"
                            placeholder="Masukkan Tahun Angkatan" required min="2000" max="2100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
