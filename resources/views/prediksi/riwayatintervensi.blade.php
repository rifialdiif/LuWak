<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="mb-3 d-flex align-items-center">
        <i class="bi bi-bell fs-3 me-2"></i>
        <span class="fw-bold fs-4">Riwayat Intervensi</span>
    </div>
    <div class="mb-4 text-muted" style="font-size:1rem;">Daftar semua notifikasi intervensi yang telah dikirim</div>
    <div id="riwayatIntervensiWrapper" class="d-flex flex-column gap-2">
        @if (isset($riwayatNotifikasi) && count($riwayatNotifikasi))
            @foreach ($riwayatNotifikasi as $notif)
                <div class="position-relative border rounded-3 p-2 ps-3" style="min-height:80px;">
                    <div class="position-absolute top-0 start-0 h-100"
                        style="width:6px; background:#fd9d0d; border-radius:12px 0 0 12px;"></div>
                    <div class="d-flex align-items-center mb-1 ps-2">
                        @if ($notif->jenis_kirim === 'whatsapp')
                            <i class="bi bi-telephone fs-6 me-2 text-success"></i>
                            <span class="fw-bold small">WhatsApp</span>
                        @else
                            <i class="bi bi-envelope fs-6 me-2 text-primary"></i>
                            <span class="fw-bold small">EMAIL</span>
                        @endif
                        <span class="badge bg-dark ms-auto rounded-pill px-2 py-1"
                            style="font-size:0.85rem; position:absolute; top:10px; right:10px;">
                            {{ $notif->status_kirim ? 'SENT' : 'FAILED' }}
                        </span>
                    </div>
                    <div class="small ps-2"><b>Pengirim:</b> {{ $notif->user->nama ?? '-' }}</div>
                    <div class="small ps-2"><b>Penerima:</b>
                        {{ $notif->jenis_kirim === 'whatsapp' ? $mahasiswa->no_hp_orang_tua ?? '-' : $mahasiswa->email_ortu ?? '-' }}
                    </div>
                    <div class="small ps-2"><b>Pesan:</b> {{ $notif->isi_pesan }}</div>
                    <div class="text-muted small mt-1 ps-2">
                        {{ \Carbon\Carbon::parse($notif->waktu_kirim)->format('d/m/Y, H.i.s') }}</div>
                </div>
            @endforeach
        @else
            <div class="text-muted text-center">Belum ada riwayat intervensi.</div>
        @endif
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchRiwayatIntervensi() {
                var id = @json($mahasiswa->id_mahasiswa ?? null);
                if (!id) return;
                fetch(`/prediksi/${id}/riwayat-intervensi-ajax`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.riwayat_html) {
                            // Ambil hanya isi notifikasi, tanpa header/deskripsi
                            const temp = document.createElement('div');
                            temp.innerHTML = data.riwayat_html;
                            const inner = temp.querySelector('#riwayatIntervensiWrapper');
                            if (inner) {
                                document.getElementById('riwayatIntervensiWrapper').innerHTML = inner.innerHTML;
                            }
                        }
                    });
            }
            // Fetch setiap 10 detik
            setInterval(fetchRiwayatIntervensi, 5000);
        });
    </script>
@endpush
