<div class="modal fade" id="editMahasiswaModal" tabindex="-1" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-mahasiswa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control bg-light text-dark" id="edit_nama_mahasiswa"
                                    value="" disabled style="background-color: #e9ecef;">
                                <input type="hidden" id="edit_id_user_hidden" name="id_user">
                            </div>
                            <div class="mb-3">
                                <label for="edit_id_angkatan" class="form-label">Angkatan</label>
                                <select class="form-select" id="edit_id_angkatan" name="id_angkatan" required>
                                    @foreach ($angkatans as $angkatan)
                                        <option value="{{ $angkatan->id_angkatan }}">{{ $angkatan->tahun_angkatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_no_hp_orang_tua" class="form-label">No HP Orang Tua</label>
                                <input type="text" class="form-control" id="edit_no_hp_orang_tua"
                                    name="no_hp_orang_tua" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_email_ortu" class="form-label">E-mail Orang Tua</label>
                                <input type="email" class="form-control" id="edit_email_ortu" name="email_ortu"
                                    required>
                            </div>
                        </div>
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
