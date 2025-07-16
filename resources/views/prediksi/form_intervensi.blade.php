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
                <form id="formIntervensi" method="POST" action="{{ route('notifikasi.kirimIntervensi') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">Penerima</label>
                        <input type="text" class="form-control" name="penerima" id="penerima"
                            placeholder="Nama penerima" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">Metode Pengiriman</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="metode" id="metodeEmail"
                                    value="email" checked required>
                                <label class="form-check-label d-flex align-items-center" for="metodeEmail">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                            </div>
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="metode" id="metodeSms"
                                    value="sms" required>
                                <label class="form-check-label d-flex align-items-center" for="metodeSms">
                                    <i class="bi bi-telephone me-2"></i>WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">Email/Nomor HP</label>
                        <input type="text" class="form-control" name="nomor_email" id="nomor_email"
                            placeholder="Email/Nomor HP orang tua" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">Template Pesan</label>
                        <select class="form-select" id="template_pesan" name="template_pesan">
                            <option value="">-- Pilih Template Pesan --</option>
                            <option value="peringatan">Peringatan Akademik</option>
                            <option value="perkembangan">Informasi Perkembangan</option>
                            <option value="undangan">Undangan Diskusi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">Pesan</label>
                        <textarea class="form-control" name="pesan" id="pesan" rows="4" placeholder="Tulis pesan intervensi..."
                            required></textarea>
                        <div class="invalid-feedback" id="pesanError" style="display:none;">
                            Pesan harus diisi jika tidak memilih template pesan.
                        </div>
                    </div>
                    <input type="hidden" name="id_mahasiswa" value="{{ $mahasiswa ? $mahasiswa->id_mahasiswa : '' }}">
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

@push('script')
    <script>
        window.mahasiswaData = {
            nama: "{{ $mahasiswa->user->nama ?? '' }}",
            nim: "{{ $mahasiswa->user->nip_nim ?? '' }}",
            prodi: "{{ $mahasiswa->user->prodi->nama_prodi ?? '' }}"
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('template_pesan');
            const pesanTextarea = document.getElementById('pesan');
            templateSelect.addEventListener('change', function() {
                const m = window.mahasiswaData || {};
                let text = '';
                if (this.value === 'peringatan') {
                    text =
                        `Yth. Orang Tua/Wali dari ${m.nama} - ${m.nim},\n\nKami dari Program Studi ${m.prodi} ingin menyampaikan bahwa saat ini kami sedang memberikan perhatian khusus terhadap perkembangan akademik ${m.nama}.\n\nBeberapa indikator menunjukkan perlunya dukungan tambahan dari berbagai pihak, termasuk dari keluarga. Kami sangat menghargai jika Bapak/Ibu dapat memberikan semangat dan perhatian lebih kepada putra/putri Bapak/Ibu dalam menjalani proses perkuliahan.\n\nTerima kasih atas kerja sama dan perhatiannya.`;
                } else if (this.value === 'perkembangan') {
                    text =
                        `Yth. Orang Tua/Wali dari ${m.nama} - ${m.nim},\n\nKami dari Program Studi ${m.prodi} ingin menginformasikan bahwa ${m.nama} saat ini menunjukkan perkembangan akademik yang cukup baik berdasarkan pemantauan internal kami.\n\nKami akan terus memberikan pendampingan dan evaluasi berkala untuk memastikan proses pembelajaran berjalan secara optimal. Dukungan Bapak/Ibu dari rumah tentu sangat berarti bagi semangat belajar putra/putri Bapak/Ibu.\n\nTerima kasih atas perhatian dan kerja samanya.`;
                } else if (this.value === 'undangan') {
                    text =
                        `Yth. Orang Tua/Wali dari ${m.nama} - ${m.nim},\n\nKami mengundang Bapak/Ibu untuk mengikuti kegiatan diskusi akademik yang akan dilaksanakan bersama pihak Program Studi ${m.prodi}.\n\nDiskusi ini bertujuan untuk menyampaikan perkembangan pembelajaran ${m.nama} dan membangun komunikasi yang lebih baik antara pihak kampus dan orang tua/wali, guna mendukung proses pendidikan yang lebih optimal.\n\nInformasi terkait jadwal dan teknis pelaksanaan kegiatan akan kami sampaikan lebih lanjut melalui media ini.\n\nAtas perhatian dan kesediaan Bapak/Ibu, kami ucapkan terima kasih.`;
                }
                pesanTextarea.value = text;
            });

            // WhatsApp redirect logic
            const form = document.getElementById('formIntervensi');
            form.addEventListener('submit', function(e) {
                const metode = document.querySelector('input[name="metode"]:checked').value;
                if (metode === 'sms') {
                    const nomor = document.getElementById('nomor_email').value.replace(/[^0-9]/g, '');
                    const pesan = document.getElementById('pesan').value;
                    if (!nomor || !pesan) {
                        alert('Nomor WhatsApp dan pesan harus diisi!');
                        return;
                    }
                    // Pastikan nomor sudah format internasional (misal: 628xxxx)
                    let nomorWA = nomor;
                    if (nomorWA.startsWith('0')) {
                        nomorWA = '62' + nomorWA.substring(1);
                    }
                    const url = `https://wa.me/${nomorWA}?text=${encodeURIComponent(pesan)}`;
                    window.open(url, '_blank');
                }
                // Jika email, biarkan submit ke server
            });
        });
    </script>
@endpush
