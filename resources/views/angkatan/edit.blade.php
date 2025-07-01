<div class="modal fade" id="editAngkatanModal" tabindex="-1" aria-labelledby="editAngkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-angkatan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editAngkatanModalLabel">Edit Angkatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_tahun_angkatan" class="form-label">Tahun Angkatan</label>
                        <input type="number" class="form-control" id="edit_tahun_angkatan" name="tahun_angkatan"
                            required min="2000" max="2100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
