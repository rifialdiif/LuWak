<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="mb-3 d-flex align-items-center">
        <i class="bi bi-bell fs-3 me-2"></i>
        <span class="fw-bold fs-4">Riwayat Intervensi</span>
    </div>
    <div class="mb-4 text-muted" style="font-size:1rem;">Daftar semua notifikasi intervensi yang telah dikirim</div>
    <div class="d-flex flex-column gap-2">
        @forelse($riwayatNotifikasi as $notif)
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
                </div>
                <div class="small ps-2"><b>Pengirim:</b> {{ $notif->user->nama ?? '-' }}</div>
                <div class="small ps-2"><b>Penerima:</b>
                    {{ $notif->jenis_kirim === 'whatsapp' ? $mahasiswa->no_hp_orang_tua ?? '-' : $mahasiswa->email_ortu ?? '-' }}
                </div>
                <div class="small ps-2"><b>Pesan:</b> {{ $notif->isi_pesan }}</div>
                <div class="text-muted small ps-2">
                    {{ \Carbon\Carbon::parse($notif->waktu_kirim)->format('d/m/Y, H.i.s') }}
                </div>
                <span
                    class="badge {{ $notif->status_kirim ? 'bg-success' : 'bg-danger' }} ms-auto rounded-pill px-2 py-1 custom-badge-intervensi">
                    {{ $notif->status_kirim ? 'SENT' : 'FAILED' }}
                </span>
            </div>
        @empty
            <div class="text-muted text-center">Belum ada riwayat intervensi.</div>
        @endforelse
    </div>
</div>

@push('styles')
    <style>
        .custom-badge-intervensi {
            font-size: 0.95rem;
            position: absolute;
            top: 10px;
            right: 10px;
            max-width: 70vw;
            white-space: normal;
            text-align: right;
            z-index: 2;
        }

        @media (max-width: 576px) {
            .custom-badge-intervensi {
                position: static;
                display: block;
                margin: 10px 0 0 auto;
                font-size: 0.75rem;
                text-align: right;
                max-width: 100%;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>
@endpush
