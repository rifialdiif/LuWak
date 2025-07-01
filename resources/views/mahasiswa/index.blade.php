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
                                        <th>Angkatan</th>
                                        <th>DPA</th>
                                        <th>No HP Orang Tua</th>
                                        <th>E-mail Orang Tua</th>
                                        <th>Hasil Prediksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswas as $i => $mhs)
                                        <tr>
                                            <td>{{ $mhs->user->nip_nim ?? '-' }}</td>
                                            <td>{{ $mhs->user->nama ?? '-' }}</td>
                                            <td>{{ $mhs->angkatan->tahun_angkatan ?? '-' }}</td>
                                            <td>{{ $mhs->dpa && $mhs->dpa->user ? $mhs->dpa->user->nama : '-' }}</td>
                                            <td>{{ $mhs->no_hp_orang_tua }}</td>
                                            <td>{{ $mhs->email_ortu }}</td>
                                            <td>{{ $mhs->status_prediksi_kelulusan ?? '-' }}</td>
                                            <td>
                                                <a href="#" class="btn icon btn-warning btn-edit-mahasiswa"
                                                    data-id="{{ $mhs->id_mahasiswa }}" data-id_user="{{ $mhs->id_user }}"
                                                    data-id_angkatan="{{ $mhs->id_angkatan }}"
                                                    data-no_hp="{{ $mhs->no_hp_orang_tua }}"
                                                    data-email_ortu="{{ $mhs->email_ortu }}"
                                                    data-status="{{ $mhs->status_kelulusan }}" data-bs-toggle="modal"
                                                    data-bs-target="#editMahasiswaModal">
                                                    <i class="uil-pen"></i>
                                                </a>
                                                <form id="form-delete-{{ $mhs->id_mahasiswa }}"
                                                    action="{{ route('mahasiswa.destroy', $mhs->id_mahasiswa) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn icon btn-danger btn-delete"
                                                        data-url="{{ route('mahasiswa.destroy', $mhs->id_mahasiswa) }}">
                                                        <i class="uil-trash-alt"></i>
                                                    </button>
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
    @include('mahasiswa.create')
    {{-- @include('mahasiswa.edit') --}}
@endsection

@push('script')
    <script>
        // $(document).on('click', '.btn-edit-mahasiswa', function() {
        //     var id = $(this).data('id');
        //     $('#form-edit-mahasiswa').attr('action', '/mahasiswa/' + id);
        //     $('#edit_id_user').val($(this).data('id_user'));
        //     $('#edit_id_angkatan').val($(this).data('id_angkatan'));
        //     $('#edit_no_hp_orang_tua').val($(this).data('no_hp'));
        //     $('#edit_email_ortu').val($(this).data('email_ortu'));
        //     $('#edit_status_kelulusan').val($(this).data('status'));
        // });
        // // Delete confirmation
        // $(document).on('click', '.btn-delete', function(e) {
        //     e.preventDefault();
        //     var url = $(this).data('url');
        //     var id = url.split('/').pop();
        //     Swal.fire({
        //         title: "Yakin ingin menghapus data ini?",
        //         text: "Data yang dihapus tidak dapat dikembalikan!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#d33",
        //         cancelButtonColor: "#3085d6",
        //         confirmButtonText: "Ya, hapus!",
        //         cancelButtonText: "Batal"
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $('#form-delete-' + id).submit();
        //         }
        //     });
        // });
    </script>
@endpush
