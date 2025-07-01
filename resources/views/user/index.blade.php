@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
        </div>
    </div>

    {{-- <ul class="nav nav-tabs mb-3" id="userTabMenu" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
                role="tab" aria-controls="admin" aria-selected="true">Admin</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="dosen-tab" data-bs-toggle="tab" data-bs-target="#dosen" type="button"
                role="tab" aria-controls="dosen" aria-selected="false">Dosen</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button"
                role="tab" aria-controls="mahasiswa" aria-selected="false">Mahasiswa</button>
        </li>
    </ul> --}}
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
                        <h4 class="page-title header-title mb-0">Data User</h4>
                        <div class="btn-list text-right d-flex">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#userModal">
                                <i data-feather="plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="multi-item-preview">
                            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIP/NIM</th>
                                        <th>Nama</th>
                                        <th>E-mail</th>
                                        <th>Role</th>
                                        <th>Prodi</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $i => $user)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $user->nip_nim }}</td>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst($user->role) }}</td>
                                            <td>{{ $user->prodi ? $user->prodi->nama_prodi : '-' }}</td>
                                            {{-- <td>
                                                @if ($user->is_active)
                                                    <button type="button" class="btn btn-sm btn-success rounded-pill"
                                                        disabled>Aktif</button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                        disabled>Tidak Aktif</button>
                                                @endif
                                            </td> --}}
                                            <td>
                                                <a href="#" class="btn icon btn-warning btn-edit-user"
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                    data-id="{{ $user->id_user }}" data-nip_nim="{{ $user->nip_nim }}"
                                                    data-nama="{{ $user->nama }}" data-email="{{ $user->email }}"
                                                    data-role="{{ $user->role }}" data-id_prodi="{{ $user->id_prodi }}">
                                                    <i class="uil-pen"></i>
                                                </a>
                                                <a href="#" class="btn icon btn-danger btn-delete"
                                                    data-url="{{ route('user.destroy', $user->id_user) }}">
                                                    <i class="uil-trash-alt"></i>
                                                </a>
                                                <form id="form-delete-{{ $user->id_user }}"
                                                    action="{{ route('user.destroy', $user->id_user) }}" method="POST"
                                                    style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
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
    @include('user.create')
    @include('user.edit')
@endsection

@push('script')
    <script>
        $(document).on('click', '.btn-edit-user', function() {
            $('#edit_id_user').val($(this).data('id'));
            $('#edit_nip_nim').val($(this).data('nip_nim'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_email').val($(this).data('email'));
            $('#edit_role').val($(this).data('role')).trigger('change');
            $('#edit_prodi').val($(this).data('id_prodi')).trigger('change');
            // Set action form
            $('#editUserModal form').attr('action', '/user/' + $(this).data('id'));
        });
    </script>
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
    </script>
@endpush
