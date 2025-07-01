<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';
    protected $primaryKey = 'id_angkatan';
    public $timestamps = false;

    protected $fillable = [
        'tahun_angkatan',
    ];

    /**
     * Get all of the mahasiswas for the Angkatan.
     */
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'id_angkatan', 'id_angkatan');
    }
}
