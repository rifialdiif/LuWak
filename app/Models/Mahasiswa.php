<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_angkatan',
        'no_hp_orang_tua',
        'email_ortu',
        'status_prediksi_kelulusan',
    ];

    /**
     * Get the user record associated with the mahasiswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the angkatan that the mahasiswa belongs to.
     */
    public function angkatan(): BelongsTo
    {
        return $this->belongsTo(Angkatan::class, 'id_angkatan', 'id_angkatan');
    }



    /**
     * Get the DPA record associated with the mahasiswa.
     */
    public function dpa(): HasOne
    {
        return $this->hasOne(Dpa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get the riwayat akademik for the mahasiswa.
     */
    public function riwayatAkademik(): HasOne
    {
        return $this->hasOne(RiwayatAkademik::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get all of the prediksi records for the Mahasiswa.
     */
    public function prediksi(): HasMany
    {
        return $this->hasMany(Prediksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get all of the notifikasi records for the Mahasiswa.
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Scope a query to only include mahasiswa who do not have a DPA.
     */
    public function scopeWithoutDpa($query)
    {
        return $query->doesntHave('dpa');
    }
}
