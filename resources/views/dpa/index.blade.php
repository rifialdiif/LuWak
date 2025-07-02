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
                                data-bs-target="#dpaModal">
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
                                        <th>Nama DPA</th>
                                        <th>Prodi</th>
                                        <th>Jumlah Mahasiswa</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $i => $dpa)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $dpa['nama_dpa'] }}</td>
                                            <td>{{ $dpa['prodi'] }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailMhsModal{{ $dpa['id_dosen'] }}">
                                                    <span class="badge bg-primary">{{ $dpa['jumlah_mahasiswa'] }}</span>
                                                    <span>Detail</span>
                                                    <i class="uil uil-eye"></i>
                                                </button>
                                                @include('dpa.detail', ['dpa' => $dpa])
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
    @include('dpa.create')
    @include('dpa.edit')
@endsection

@push('script')
@endpush
