<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <input type="hidden" id="edit_id_user" name="id_user">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nip_nim" class="form-label">NIP/NIM</label>
                            <input type="number" class="form-control" id="edit_nip_nim" name="nip_nim"
                                placeholder="Masukkan NIP/NIM" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama"
                                placeholder="Masukkan Nama" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email"
                                placeholder="Masukkan Email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">Password (Opsional)</label>
                            <input type="password" class="form-control" id="edit_password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select select2" id="edit_role" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_prodi" class="form-label">Prodi</label>
                            <select data-plugin="customselect" class="form-select select2"
                                data-placeholder="Pilih Prodi" id="edit_prodi" name="id_prodi" required>
                                <option value=""></option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
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
