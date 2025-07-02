<div class="modal fade" id="detailMhsModal{{ $dpa['id_dosen'] }}" tabindex="-1"
    aria-labelledby="detailMhsLabel{{ $dpa['id_dosen'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="detailMhsLabel{{ $dpa['id_dosen'] }}">
                    <i class="uil uil-users-alt me-2"></i>Daftar Mahasiswa DPA: <span
                        class="fw-bold">{{ $dpa['nama_dpa'] }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="badge bg-info">Prodi: {{ $dpa['prodi'] }}</span>
                    <span class="badge bg-success ms-2">Total: {{ $dpa['jumlah_mahasiswa'] }} Mahasiswa</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-detail"
                        id="datatable-detail-{{ $dpa['id_dosen'] }}">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dpa['mahasiswas'] as $j => $mhs)
                                <tr>
                                    <td>{{ $j + 1 }}</td>
                                    <td><span class="fw-semibold">{{ $mhs['nim'] }}</span></td>
                                    <td>{{ $mhs['nama'] }}</td>
                                    <td>{{ $mhs['angkatan'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editDpaMhsModal{{ $dpa['id_dosen'] }}_{{ $j }}">
                                            <i class="uil-pen"></i>
                                        </button>
                                        <form id="form-delete-{{ $mhs['id_dpa'] }}"
                                            action="{{ route('dpa.destroy', $mhs['id_dpa']) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                data-url="{{ route('dpa.destroy', $mhs['id_dpa']) }}">
                                                <i class="uil uil-trash-alt"></i>
                                            </button>
                                        </form>
                                        <!-- Modal Edit DPA Mahasiswa -->
                                        <div class="modal fade"
                                            id="editDpaMhsModal{{ $dpa['id_dosen'] }}_{{ $j }}"
                                            tabindex="-1"
                                            aria-labelledby="editDpaMhsLabel{{ $dpa['id_dosen'] }}_{{ $j }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('dpa.update', ['id' => $mhs['id_dpa'] ?? '']) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editDpaMhsLabel{{ $dpa['id_dosen'] }}_{{ $j }}">
                                                                Edit Dosen Pembimbing untuk {{ $mhs['nama'] }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label
                                                                    for="dosen_edit_{{ $dpa['id_dosen'] }}_{{ $j }}"
                                                                    class="form-label">Pilih Dosen</label>
                                                                <select class="form-select"
                                                                    id="dosen_edit_{{ $dpa['id_dosen'] }}_{{ $j }}"
                                                                    name="id_user_dosen_pembimbing" required>
                                                                    @foreach ($dosens as $dosen)
                                                                        @if ($dosen->id_prodi == $mhs['id_prodi'])
                                                                            <option value="{{ $dosen->id_user }}"
                                                                                @if ($dosen->id_user == $dpa['id_dosen']) selected @endif>
                                                                                {{ $dosen->nama }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada mahasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var id = url.split('/').pop();
            Swal.fire({
                title: "Yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-' + id).submit();
                }
            });
        });
        // Inisialisasi DataTable pada setiap modal detail saat modal dibuka
        $(document).on('shown.bs.modal', function(e) {
            var table = $(e.target).find('.datatable-detail');
            if (table.length && !$.fn.DataTable.isDataTable(table[0])) {
                table.DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: false,
                    lengthChange: false,
                    pageLength: 5,
                    language: {
                        search: 'Cari:',
                        zeroRecords: 'Tidak ada data',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Berikutnya'
                        }
                    }
                });
            }
        });
    </script>
@endpush
