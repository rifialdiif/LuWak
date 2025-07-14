<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="mb-3 d-flex align-items-center">
        <i class="bi bi-arrow-counterclockwise fs-3 me-2"></i>
        <span class="fw-bold fs-4">Riwayat Prediksi</span>
    </div>
    <div class="mb-4 text-muted" style="font-size:1rem;">Daftar semua prediksi yang telah dilakukan</div>
    <div class="d-flex flex-column gap-2">
        @forelse($riwayatPrediksi as $prediksi)
            <div class="position-relative border rounded-3 p-2 ps-3" style="min-height:80px;">
                <div class="position-absolute top-0 start-0 h-100"
                    style="width:6px; background:#2684ff; border-radius:12px 0 0 12px;"></div>
                <div class="fw-bold small mb-1 ps-2">{{ $mahasiswa->user->nama ?? '-' }}</div>
                <div class="mb-1 small ps-2">{{ $mahasiswa->user->nip_nim ?? '-' }} -
                    {{ $mahasiswa->user->prodi->nama_prodi ?? '-' }}</div>
                <div class="text-muted small ps-2">
                    {{ \Carbon\Carbon::parse($prediksi->tanggal_prediksi)->format('d/m/Y, H.i.s') }}
                    • Confidence: {{ round($prediksi->confidence_score * 100, 1) }}%
                </div>
                <div class="small ps-2"><b>Dilakukan oleh:</b> {{ $prediksi->user->nama ?? '-' }}</div>
                <span
                    class="badge {{ $prediksi->hasil_prediksi == 1 ? 'bg-danger' : 'bg-dark' }} ms-auto rounded-pill px-2 py-1 position-absolute"
                    style="font-size:0.85rem; top:10px; right:10px;">
                    {{ $prediksi->hasil_prediksi == 1 ? 'Berisiko Lulus Tidak Tepat Waktu' : 'Lulus Tepat Waktu' }}
                </span>
            </div>
        @empty
            <div class="text-muted text-center">Belum ada riwayat prediksi.</div>
        @endforelse
    </div>
</div>
