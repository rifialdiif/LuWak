<div class="modal fade" id="dpaModal" tabindex="-1" aria-labelledby="dpaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dpa.store') }}" method="POST" id="dpaCreateForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="dpaModalLabel">Tambah DPA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="dpaTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="mahasiswa-tab" data-bs-toggle="tab"
                                data-bs-target="#mahasiswa" type="button" role="tab" aria-controls="mahasiswa"
                                aria-selected="true">Berdasarkan Mahasiswa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="angkatan-tab" data-bs-toggle="tab" data-bs-target="#angkatan"
                                type="button" role="tab" aria-controls="angkatan" aria-selected="false">Berdasarkan
                                Angkatan</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="dpaTabContent">
                        <!-- Tab Mahasiswa -->
                        <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel"
                            aria-labelledby="mahasiswa-tab">
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label for="dosen_mahasiswa" class="form-label">Pilih Dosen</label>
                                    <select class="form-select select2" id="dosen_mahasiswa">
                                        <option value="">Pilih Dosen</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->id_user }}"
                                                data-prodi="{{ $dosen->prodi->nama_prodi ?? '' }}"
                                                data-idprodi="{{ $dosen->id_prodi }}">{{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prodi</label>
                                    <input type="text" class="form-control" id="prodi_mahasiswa" readonly>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="mahasiswa_select" class="form-label">Pilih Mahasiswa</label>
                                    <select class="form-select select2" id="mahasiswa_select" multiple disabled>
                                        <option value="">Pilih Mahasiswa</option>
                                        @foreach ($mahasiswas as $mhs)
                                            <option value="{{ $mhs->id_mahasiswa }}"
                                                data-idprodi="{{ $mhs->user->id_prodi ?? '' }}">
                                                {{ $mhs->user->nip_nim ?? '-' }} - {{ $mhs->user->nama ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Angkatan -->
                        <div class="tab-pane fade" id="angkatan" role="tabpanel" aria-labelledby="angkatan-tab">
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label for="dosen_angkatan" class="form-label">Pilih Dosen</label>
                                    <select class="form-select select2" id="dosen_angkatan">
                                        <option value="">Pilih Dosen</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->id_user }}"
                                                data-prodi="{{ $dosen->prodi->nama_prodi ?? '' }}"
                                                data-idprodi="{{ $dosen->id_prodi }}">{{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prodi</label>
                                    <input type="text" class="form-control" id="prodi_angkatan" readonly>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="angkatan_select" class="form-label">Pilih Angkatan</label>
                                    <select class="form-select select2" id="angkatan_select">
                                        <option value="">Pilih Angkatan</option>
                                        @foreach ($angkatans as $angkatan)
                                            <option value="{{ $angkatan->id_angkatan }}">
                                                {{ $angkatan->tahun_angkatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitDpa">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        // Tab Mahasiswa: Update Prodi and filter Mahasiswa
        $('#dosen_mahasiswa').on('change', function() {
            var dosenId = $(this).val();
            var selected = $(this).find('option:selected');
            var prodi = selected.data('prodi') || '';
            var idprodi = selected.data('idprodi') || '';
            $('#prodi_mahasiswa').val(prodi);
            // Disable select mahasiswa sebelum AJAX selesai
            $('#mahasiswa_select').prop('disabled', true).html('<option value="">Memuat...</option>');
            if (dosenId) {
                $.get('/dpa/mahasiswa-by-dosen/' + dosenId, function(data) {
                    var options = '';
                    data.forEach(function(mhs) {
                        options += '<option value="' + mhs.id + '">' + mhs.nip_nim + ' - ' + mhs
                            .nama + '</option>';
                    });
                    $('#mahasiswa_select').html(options).prop('disabled', false);
                });
            } else {
                $('#mahasiswa_select').html('<option value="">Pilih Mahasiswa</option>').prop('disabled', true);
            }
        });
        // Tab Angkatan: Update Prodi
        $('#dosen_angkatan').on('change', function() {
            var selected = $(this).find('option:selected');
            var prodi = selected.data('prodi') || '';
            $('#prodi_angkatan').val(prodi);
        });
        // Reset form on modal close
        $('#dpaModal').on('hidden.bs.modal', function() {
            $('#dpaCreateForm')[0].reset();
            $('#prodi_mahasiswa').val('');
            $('#prodi_angkatan').val('');
            $('#mahasiswa_select option').show();
            // Hapus name dan required dari semua select
            $('#dosen_mahasiswa, #mahasiswa_select, #dosen_angkatan, #angkatan_select').removeAttr('name').prop(
                'required', false);
            $('#btnSubmitDpa').prop('disabled', true);
        });
        // On submit, set name attribute only for active tab fields
        $('#dpaCreateForm').on('submit', function() {
            if ($('#mahasiswa-tab').hasClass('active')) {
                $('#dosen_mahasiswa').attr('name', 'id_user_dosen_pembimbing_mahasiswa').prop('required', true);
                $('#mahasiswa_select').attr('name', 'id_mahasiswa[]').prop('required', true).prop('disabled',
                    false);
                $('#dosen_angkatan').removeAttr('name').prop('required', false);
                $('#angkatan_select').removeAttr('name').prop('required', false);
            } else {
                $('#dosen_angkatan').attr('name', 'id_user_dosen_pembimbing_angkatan').prop('required', true);
                $('#angkatan_select').attr('name', 'id_angkatan').prop('required', true);
                $('#dosen_mahasiswa').removeAttr('name').prop('required', false);
                $('#mahasiswa_select').removeAttr('name').prop('required', false);
            }
        });
        // Validasi frontend: enable/disable tombol submit sesuai field wajib
        function checkFormDpa() {
            if ($('#mahasiswa-tab').hasClass('active')) {
                var dosen = $('#dosen_mahasiswa').val();
                var mhs = $('#mahasiswa_select').val();
                if (dosen && mhs && mhs.length > 0) {
                    $('#btnSubmitDpa').prop('disabled', false);
                } else {
                    $('#btnSubmitDpa').prop('disabled', true);
                }
            } else {
                var dosen = $('#dosen_angkatan').val();
                var angkatan = $('#angkatan_select').val();
                if (dosen && angkatan) {
                    $('#btnSubmitDpa').prop('disabled', false);
                } else {
                    $('#btnSubmitDpa').prop('disabled', true);
                }
            }
        }
        $('#dosen_mahasiswa, #mahasiswa_select, #dosen_angkatan, #angkatan_select').on('change', checkFormDpa);
        $('#dpaTab button[data-bs-toggle="tab"]').on('shown.bs.tab', checkFormDpa);
        // Inisialisasi awal
        $(document).ready(function() {
            checkFormDpa();
        });
    </script>
@endpush
