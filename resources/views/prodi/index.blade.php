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
                                data-bs-target="#prodiModal">
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
                                        <th>Nama Prodi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prodis as $i => $prodi)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $prodi->nama_prodi }}</td>
                                            <td>
                                                <a href="#" class="btn icon btn-warning btn-edit-prodi"
                                                    data-bs-toggle="modal" data-bs-target="#editProdiModal"
                                                    data-id="{{ $prodi->id_prodi }}" data-nama="{{ $prodi->nama_prodi }}">
                                                    <i class="uil-pen"></i>
                                                </a>
                                                <a href="#" class="btn icon btn-danger btn-delete"
                                                    data-url="{{ route('prodi.destroy', $prodi->id_prodi) }}"
                                                    data-method="delete">
                                                    <i class="uil-trash-alt"></i>
                                                </a>
                                                <form id="form-delete-{{ $prodi->id_prodi }}"
                                                    action="{{ route('prodi.destroy', $prodi->id_prodi) }}" method="POST"
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
    @include('prodi.create')
    @include('prodi.edit')
@endsection

@push('script')
    <script>
        $(document).on('click', '.btn-edit-prodi', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            $('#editProdiModal input[name="nama_prodi"]').val(nama);
            $('#editProdiModal form').attr('action', '/prodi/' + id);
        });
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
