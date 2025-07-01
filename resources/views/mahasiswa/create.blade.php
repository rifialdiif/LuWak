<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="mahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mhs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mahasiswaModalLabel">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_user" class="form-label">Pilih Mahasiswa</label>
                                <select data-plugin="customselect" class="form-select select2"
                                    data-placeholder="Pilih Mahasiswa" id="id_user" name="id_user" required>
                                    <option value=""></option>
                                    @foreach ($users as $user)
                                        @if ($user->role === 'mahasiswa' && !$mahasiswas->contains('id_user', $user->id_user))
                                            <option value="{{ $user->id_user }}">{{ $user->nip_nim }} -
                                                {{ $user->nama }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_angkatan" class="form-label">Angkatan</label>
                                <select data-plugin="customselect" class="form-select select2"
                                    data-placeholder="Pilih Tahun Angkatan" id="id_angkatan" name="id_angkatan"
                                    required>
                                    <option value=""></option>
                                    @foreach ($angkatans as $angkatan)
                                        <option value="{{ $angkatan->id_angkatan }}">{{ $angkatan->tahun_angkatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_hp_orang_tua" class="form-label">No HP Orang Tua</label>
                                <input type="number" class="form-control" id="no_hp_orang_tua" name="no_hp_orang_tua"
                                    placeholder="Masukkan No HP Orang Tua" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_ortu" class="form-label">E-mail Orang Tua</label>
                                <input type="email" class="form-control" id="email_ortu" name="email_ortu"
                                    placeholder="Masukkan Email Orang Tua" required>
                            </div>
                        </div>
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
