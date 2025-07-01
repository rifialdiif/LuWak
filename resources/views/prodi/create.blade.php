<div class="modal fade" id="prodiModal" tabindex="-1" aria-labelledby="prodiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('prodi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="prodiModalLabel">Tambah Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_prodi" class="form-label">Nama Prodi</label>
                        <input type="text" class="form-control" id="nama_prodi" name="nama_prodi"
                            placeholder="Masukkan Nama Prodi" required>
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
