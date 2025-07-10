@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-2">
                            <span class="fw-bold" style="font-size:1.1rem;">Pilih Mahasiswa</span>
                        </div>
                        <div class="mb-2 text-muted" style="font-size:1rem;">
                            Silakan pilih Mahasiswa terlebih dahulu<br>untuk melihat prediksi kelulusan.
                        </div>
                        <form id="formCariMahasiswa" method="GET" class="d-flex align-items-center gap-3 mt-3">
                            <div class="flex-grow-1">
                                <select class="form-select select2" id="mahasiswa_id" name="mahasiswa_id"
                                    data-plugin="customselect" data-placeholder="Pilih Mahasiswa">
                                    <option value=""></option>
                                    @foreach ($mahasiswas ?? [] as $mhs)
                                        <option value="{{ $mhs->id_mahasiswa }}">{{ $mhs->nama }} ({{ $mhs->nim }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="btnLanjut" class="btn btn-primary fw-bold px-4"
                                style="height:48px; min-width:120px; border:none;">Lanjut</button>
                        </form>
                        <div id="hasilPencarian" class="mt-4">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
            $('#btnLanjut').on('click', function() {
                var id = $('#mahasiswa_id').val();
                if (id) {
                    window.location.href = "{{ url('prediksi') }}/" + id;
                } else {
                    alert('Silakan pilih mahasiswa terlebih dahulu!');
                }
            });
        });
    </script>
@endpush
