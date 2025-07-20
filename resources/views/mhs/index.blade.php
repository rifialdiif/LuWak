@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title header-title mb-0">Data {{ $title }}</h4>
                        <div class="btn-list text-right d-flex">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#mahasiswaModal">
                                <i data-feather="plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="multi-item-preview">
                            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Prodi</th>
                                        <th>Angkatan</th>
                                        <th>DPA</th>
                                        <th>No HP Orang Tua</th>
                                        <th>E-mail Orang Tua</th>
                                        {{-- <th>Hasil Prediksi</th> --}}
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswas as $i => $mhs)
                                        <tr>
                                            <td>{{ $mhs->user->nip_nim ?? '-' }}</td>
                                            <td>{{ $mhs->user->nama ?? '-' }}</td>
                                            <td>{{ $mhs->user->prodi->nama_prodi ?? '-' }}</td>
                                            <td>{{ $mhs->angkatan->tahun_angkatan ?? '-' }}</td>
                                            <td>{{ $mhs->dpa && $mhs->dpa->dosenPembimbing ? $mhs->dpa->dosenPembimbing->nama : '-' }}
                                            </td>
                                            <td>{{ $mhs->no_hp_orang_tua }}</td>
                                            <td>{{ $mhs->email_ortu }}</td>
                                            {{-- <td>{{ $mhs->status_prediksi_kelulusan ?? '-' }}</td> --}}
                                            <td>
                                                <a href="#" class="btn icon btn-warning btn-edit-mahasiswa"
                                                    data-bs-toggle="modal" data-bs-target="#editMahasiswaModal"
                                                    data-id="{{ $mhs->id_mahasiswa }}" data-id_user="{{ $mhs->id_user }}"
                                                    data-nama="{{ $mhs->user->nama ?? '' }}"
                                                    data-id_angkatan="{{ $mhs->angkatan->id_angkatan ?? '' }}"
                                                    data-no_hp="{{ $mhs->no_hp_orang_tua }}"
                                                    data-email_ortu="{{ $mhs->email_ortu }}"
                                                    data-status="{{ $mhs->status_kelulusan }}">
                                                    <i class="uil-pen"></i>
                                                </a>
                                                <form id="form-delete-{{ $mhs->id_mahasiswa }}"
                                                    action="{{ route('mhs.destroy', $mhs->id_mahasiswa) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn icon btn-danger btn-delete"
                                                        data-url="{{ route('mhs.destroy', $mhs->id_mahasiswa) }}">
                                                        <i class="uil-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    @include('mhs.create')
    @include('mhs.edit')
@endsection

@push('script')
    <script>
        // Cegah reset pada input nama mahasiswa
        $('#form-edit-mahasiswa').on('reset', function(e) {
            setTimeout(function() {
                var lastNama = $('#edit_nama_mahasiswa').data('last');
                if (lastNama) {
                    $('#edit_nama_mahasiswa').val(lastNama);
                }
            }, 10);
        });
        $(document).on('click', '.btn-edit-mahasiswa', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var id_user = $(this).data('id_user');
            // Set action ke route update (PUT) sesuai resource
            $('#form-edit-mahasiswa').attr('action', '/mhs/' + id);
            $('#edit_nama_mahasiswa').val(nama).data('last', nama); // input text disabled
            $('#edit_id_user_hidden').val(id_user); // input hidden
            $('#edit_id_angkatan').val($(this).data('id_angkatan'));
            $('#edit_no_hp_orang_tua').val($(this).data('no_hp'));
            $('#edit_email_ortu').val($(this).data('email_ortu'));
            $('#edit_status_kelulusan').val($(this).data('status'));
        });
        // Delete confirmation
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
    </script>
@endpush
