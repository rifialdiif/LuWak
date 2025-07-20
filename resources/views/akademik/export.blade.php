<!-- Modal Export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="bi bi-download me-2"></i>Export Data Riwayat Akademik
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('akademik.exportCsv') }}" method="POST" id="exportForm">
                    @csrf
                    <div class="mb-3">
                        <label for="angkatan_export" class="form-label">
                            <i class="bi bi-funnel me-1"></i>Pilih Angkatan
                        </label>
                        <select class="form-select" id="angkatan_export" name="angkatan" required>
                            <option value="">-- Pilih Angkatan --</option>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Pilih angkatan untuk mengexport data riwayat akademik dalam format CSV
                        </div>
                    </div>

                    <!-- Informasi File yang akan diunduh -->
                    <div id="exportInfo" class="mb-3" style="display: none;">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-file-earmark-text me-1"></i>Informasi File
                            </h6>
                            <div id="fileInfo">
                                <!-- Informasi file akan ditampilkan di sini -->
                            </div>
                        </div>
                    </div>

                    <!-- Alert untuk data kosong -->
                    <div id="emptyDataAlert" class="mb-3" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <span id="emptyDataMessage"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="submit" form="exportForm" class="btn btn-success" id="exportSubmitBtn">
                    <i class="bi bi-download me-1"></i>Export CSV
                </button>
            </div>
        </div>
    </div>
</div>
