@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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
                                        <option value="{{ $mhs->id_mahasiswa }}">
                                            {{ $mhs->nama }} ({{ $mhs->nim }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="mahasiswaError">
                                    Silakan pilih mahasiswa terlebih dahulu
                                </div>
                            </div>
                            <button type="button" id="btnLanjut" class="btn btn-primary fw-bold px-4 position-relative"
                                style="height:48px; min-width:120px; border:none; transition: all 0.3s ease;">
                                <span id="btnText">Lanjut</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </form>
                        <div id="hasilPencarian" class="mt-4">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        /* Custom shake animation */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake-animation {
            animation: shake 0.6s ease-in-out;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih Mahasiswa'
            });

            // Function to show validation error
            function showValidationError() {
                const selectElement = $('#mahasiswa_id');
                const select2Container = selectElement.next('.select2-container');

                // Add invalid class to select2
                select2Container.find('.select2-selection--single').addClass('is-invalid');

                // Show error message
                $('#mahasiswaError').show();

                // Shake animation for select
                select2Container.addClass('shake-animation');
                setTimeout(() => {
                    select2Container.removeClass('shake-animation');
                }, 600);
            }

            // Function to clear validation error
            function clearValidationError() {
                const selectElement = $('#mahasiswa_id');
                const select2Container = selectElement.next('.select2-container');

                // Remove invalid class
                select2Container.find('.select2-selection--single').removeClass('is-invalid');

                // Hide error message
                $('#mahasiswaError').hide();
            }

            // Handle select change
            $('#mahasiswa_id').on('change', function() {
                if ($(this).val()) {
                    clearValidationError();
                }
            });

            $('#btnLanjut').on('click', function() {
                const btn = $(this);
                const btnText = $('#btnText');
                const btnSpinner = $('#btnSpinner');
                const id = $('#mahasiswa_id').val();

                if (!id) {
                    showValidationError();
                    return;
                }

                // Show loading state
                btn.prop('disabled', true);
                btnText.text('Memproses...');
                btnSpinner.removeClass('d-none');

                // Navigate to prediction page
                setTimeout(() => {
                    window.location.href = "{{ url('prediksi') }}/" + id;
                }, 500);
            });

            // Also show validation when form is submitted without selection
            $('#formCariMahasiswa').on('submit', function(e) {
                var id = $('#mahasiswa_id').val();
                if (!id) {
                    e.preventDefault();
                    showValidationError();
                    return;
                }
            });

            // Add keyboard support
            $(document).on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    $('#btnLanjut').click();
                }
            });
        });
    </script>
@endpush
