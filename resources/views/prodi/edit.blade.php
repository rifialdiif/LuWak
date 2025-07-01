<div class="modal fade" id="editProdiModal" tabindex="-1" aria-labelledby="prodiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Biarkan action kosong, akan diisi oleh JavaScript --}}
            <form method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="prodiModalLabel">Edit Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_prodi_edit" class="form-label">Nama Prodi</label>
                        {{-- Gunakan id yang berbeda untuk input edit agar tidak konflik --}}
                        <input type="text" class="form-control" id="nama_prodi_edit" name="nama_prodi"
                            placeholder="Masukkan Nama Prodi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
