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
                                data-bs-target="#angkatanModal">
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
                                        <th>Angkatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($angkatans as $i => $angkatan)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $angkatan->tahun_angkatan }}</td>
                                            <td>
                                                <a href="#" class="btn icon btn-warning btn-edit-angkatan"
                                                    data-id="{{ $angkatan->id_angkatan }}"
                                                    data-tahun="{{ $angkatan->tahun_angkatan }}" data-bs-toggle="modal"
                                                    data-bs-target="#editAngkatanModal">
                                                    <i class="uil-pen"></i>
                                                </a>
                                                <a href="#" class="btn icon btn-danger btn-delete"
                                                    data-url="{{ route('angkatan.destroy', $angkatan->id_angkatan) }}">
                                                    <i class="uil-trash-alt"></i>
                                                </a>
                                                <form id="form-delete-{{ $angkatan->id_angkatan }}"
                                                    action="{{ route('angkatan.destroy', $angkatan->id_angkatan) }}"
                                                    method="POST" style="display:none;">
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
    @include('angkatan.create')
    @include('angkatan.edit')
@endsection

@push('script')
    @push('script')
        <script>
            $(document).on('click', '.btn-edit-angkatan', function() {
                var id = $(this).data('id');
                var tahun = $(this).data('tahun');
                $('#edit_tahun_angkatan').val(tahun);
                $('#form-edit-angkatan').attr('action', '/angkatan/' + id);
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
@endpush
