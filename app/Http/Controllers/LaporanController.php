<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Angkatan;
use App\Models\Prodi;
use App\Models\Prediksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    /**
     * Display the laporan index page
     */
    public function index()
    {
        try {
            // Check if user is admin
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access. Only admin can access this feature.');
            }

            $angkatans = Angkatan::orderBy('tahun_angkatan', 'desc')->get();
            $prodis = Prodi::orderBy('nama_prodi')->get();

            return view('laporan.index', compact('angkatans', 'prodis'));
        } catch (\Exception $e) {
            Log::error('Error accessing laporan index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengakses halaman laporan.');
        }
    }

    /**
     * Generate PDF laporan
     */
    public function generatePdf(Request $request)
    {
        try {
            // Check if user is admin
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access. Only admin can access this feature.');
            }

            // Validate request
            $request->validate([
                'angkatan_id' => 'nullable|exists:angkatan,id_angkatan',
                'prodi_id' => 'nullable|exists:prodi,id_prodi',
            ], [
                'angkatan_id.exists' => 'Angkatan yang dipilih tidak valid.',
                'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
            ]);

            // Check if at least one filter is applied or no filter at all
            if ($request->filled('angkatan_id') && $request->filled('prodi_id')) {
                // Both filters applied - this is fine
            } elseif (!$request->filled('angkatan_id') && !$request->filled('prodi_id')) {
                // No filters applied - this is also fine
            } else {
                // At least one filter is applied - this is fine
            }

            // Build query for mahasiswa with their latest predictions
            $query = Mahasiswa::with([
                'user.prodi',
                'angkatan',
                'prediksi' => function ($query) {
                    $query->orderBy('tanggal_prediksi', 'desc');
                }
            ]);

            // Apply filters
            if ($request->filled('angkatan_id')) {
                $query->where('id_angkatan', $request->angkatan_id);
            }

            if ($request->filled('prodi_id')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('id_prodi', $request->prodi_id);
                });
            }

            $mahasiswas = $query->get();

            // Check if data exists
            if ($mahasiswas->isEmpty()) {
                return back()->with('error', 'Tidak ada data mahasiswa yang ditemukan dengan filter yang dipilih. Silakan coba dengan filter yang berbeda.');
            }

            // Prepare data for PDF
            $data = [];
            foreach ($mahasiswas as $mahasiswa) {
                $latestPrediksi = $mahasiswa->prediksi->first();

                // Skip mahasiswa without user data
                if (!$mahasiswa->user) {
                    continue;
                }

                // Skip mahasiswa without basic required data
                if (empty($mahasiswa->user->nama) || empty($mahasiswa->user->nip_nim)) {
                    continue;
                }

                // Skip mahasiswa without prodi or angkatan data
                if (!$mahasiswa->user->prodi || !$mahasiswa->angkatan) {
                    continue;
                }

                // Skip mahasiswa without valid prodi or angkatan names
                if (empty($mahasiswa->user->prodi->nama_prodi) || empty($mahasiswa->angkatan->tahun_angkatan)) {
                    continue;
                }

                $data[] = [
                    'nim' => $mahasiswa->user->nip_nim,
                    'nama' => $mahasiswa->user->nama,
                    'prodi' => $mahasiswa->user->prodi->nama_prodi,
                    'angkatan' => $mahasiswa->angkatan->tahun_angkatan,
                    'hasil_prediksi' => $latestPrediksi && isset($latestPrediksi->hasil_prediksi) ? $this->getPrediksiLabel($latestPrediksi->hasil_prediksi) : 'Belum Ada Prediksi',
                    'confidence_score' => $latestPrediksi && is_numeric($latestPrediksi->confidence_score) ? round($latestPrediksi->confidence_score * 100, 2) : '-',
                    'tanggal_prediksi' => $latestPrediksi && $latestPrediksi->tanggal_prediksi ? date('d/m/Y', strtotime($latestPrediksi->tanggal_prediksi)) : '-',
                ];
            }

            // Check if we have valid data after processing
            if (empty($data)) {
                return back()->with('error', 'Tidak ada data mahasiswa yang valid untuk ditampilkan dalam laporan. Pastikan data mahasiswa memiliki informasi lengkap (NIM, nama, prodi, angkatan).');
            }

            // Get filter info for title
            $filterInfo = $this->getFilterInfo($request);

            // Generate PDF
            $pdf = Pdf::loadView('laporan.pdflaporan', [
                'data' => $data,
                'filterInfo' => $filterInfo,
                'generatedAt' => now()->format('d/m/Y H:i:s'),
                'totalMahasiswa' => count($data),
            ]);

            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
            ]);

            // Generate filename
            $filename = $this->generateFilename($filterInfo);

            // Return PDF as download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error generating PDF laporan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghasilkan laporan. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Get prediction label based on hasil_prediksi value
     */
    private function getPrediksiLabel($hasilPrediksi)
    {
        switch ($hasilPrediksi) {
            case 0:
                return 'Tepat Waktu';
            case 1:
                return 'Berisiko Tidak Tepat Waktu';
            default:
                return 'Tidak Diketahui';
        }
    }

    /**
     * Get filter information for title
     */
    private function getFilterInfo(Request $request)
    {
        $info = [];

        if ($request->filled('angkatan_id')) {
            $angkatan = Angkatan::find($request->angkatan_id);
            $info['angkatan'] = $angkatan->tahun_angkatan;
        }

        if ($request->filled('prodi_id')) {
            $prodi = Prodi::find($request->prodi_id);
            $info['prodi'] = $prodi->nama_prodi;
        }

        return $info;
    }

    /**
     * Generate filename for PDF
     */
    private function generateFilename($filterInfo)
    {
        $filename = 'Laporan_Prediksi_Kelulusan_';

        if (!empty($filterInfo['prodi']) && !empty($filterInfo['angkatan'])) {
            $filename .= $filterInfo['prodi'] . '_' . $filterInfo['angkatan'];
        } elseif (!empty($filterInfo['prodi'])) {
            $filename .= $filterInfo['prodi'];
        } elseif (!empty($filterInfo['angkatan'])) {
            $filename .= 'Angkatan_' . $filterInfo['angkatan'];
        } else {
            $filename .= 'Semua_Data';
        }

        $filename .= '_' . date('Y-m-d_H-i-s') . '.pdf';

        return $filename;
    }
}
