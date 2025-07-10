@extends('layouts.app')
@section('content')
    <div class="modal fade" id="modalIntervensi" tabindex="-1" aria-labelledby="modalIntervensiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalIntervensiLabel">Kirim Notifikasi Intervensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-2 text-muted" style="font-size:1rem;">
                        Kirim pesan kepada orang tua/wali mahasiswa untuk tindakan intervensi
                    </div>
                    <form id="formIntervensi" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Penerima</label>
                            <input type="text" class="form-control" name="penerima" id="penerima"
                                placeholder="Nama penerima">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Metode Pengiriman</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio" name="metode" id="metodeEmail"
                                        value="email">
                                    <label class="form-check-label d-flex align-items-center" for="metodeEmail">
                                        <i class="bi bi-envelope me-2"></i>Email
                                    </label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio" name="metode" id="metodeSms"
                                        value="sms" checked>
                                    <label class="form-check-label d-flex align-items-center" for="metodeSms">
                                        <i class="bi bi-telephone me-2"></i>WhatsApp
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Email/Nomor HP</label>
                            <input type="text" class="form-control" name="nomor_hp" id="nomor_hp"
                                placeholder="+62812345678">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Template Pesan</label>
                            <select class="form-select" id="template_pesan">
                                <option value="">-- Pilih Template Pesan --</option>
                                <option
                                    value="Yth. Orang Tua/Wali Mahasiswa,\n\nKami informasikan bahwa berdasarkan hasil evaluasi akademik, mahasiswa atas nama [Nama Mahasiswa] (NIM: [NIM]) menunjukkan adanya penurunan performa akademik yang berpotensi mempengaruhi kelulusan. Kami mohon perhatian dan dukungan Bapak/Ibu untuk memotivasi mahasiswa agar dapat meningkatkan prestasi dan menyelesaikan studi tepat waktu. Terima kasih atas perhatian dan kerja samanya.\n\nSalam hormat,\nBagian Akademik">
                                    Peringatan Akademik</option>
                                <option
                                    value="Yth. Orang Tua/Wali Mahasiswa,\n\nDengan hormat, kami sampaikan bahwa mahasiswa atas nama [Nama Mahasiswa] (NIM: [NIM]) telah menunjukkan perkembangan akademik yang baik pada semester ini. Kami mengapresiasi dukungan Bapak/Ibu dalam mendampingi mahasiswa selama proses studi.\n\nJika Bapak/Ibu memerlukan informasi lebih lanjut terkait perkembangan akademik mahasiswa, silakan menghubungi Bagian Akademik.\n\nSalam hormat,\nBagian Akademik">
                                    Informasi Perkembangan</option>
                                <option
                                    value="Yth. Orang Tua/Wali Mahasiswa,\n\nKami mengundang Bapak/Ibu untuk hadir dalam diskusi bersama pihak akademik terkait perkembangan studi mahasiswa atas nama [Nama Mahasiswa] (NIM: [NIM]). Diskusi ini bertujuan untuk mencari solusi terbaik dalam mendukung kelancaran studi mahasiswa.\n\nSilakan konfirmasi kehadiran melalui kontak yang tersedia. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.\n\nSalam hormat,\nBagian Akademik">
                                    Undangan Diskusi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Pesan</label>
                            <textarea class="form-control" name="pesan" id="pesan" rows="4" placeholder="Tulis pesan intervensi..."></textarea>
                        </div>
                        <button type="submit"
                            class="btn btn-primary w-100 py-2 rounded-3 fw-bold fs-6 d-flex align-items-center justify-content-center"
                            style="gap:0.5rem;">
                            <i data-feather="send" style="width:16px;height:16px;"></i>
                            Kirim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#modalIntervensi').modal('show');
            $('#template_pesan').on('change', function() {
                var val = $(this).val();
                $('#pesan').val(val);
            });
        });
    </script>
@endpush
