<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAkademik extends Model
{
    use HasFactory;

    protected $table = 'riwayat_akademik';
    protected $primaryKey = 'id_riwayat';
    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'ips_semester_1',
        'ips_semester_2',
        'ips_semester_3',
        'ips_semester_4',
        'status_semester_1',
        'status_semester_2',
        'status_semester_3',
        'status_semester_4',
        'total_sks_lulus',
        'total_sks_tidak_lulus',
        'dokumen_transkrip',
        'status_validasi',
        'validasi_by',
        'validasi_at',
    ];

    /**
     * Get the mahasiswa that owns the riwayat akademik.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
// This model represents the academic history of a student, including their semester grades and statuses.
// It is linked to the Mahasiswa model, which represents a student in the system.