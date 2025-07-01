<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dpa extends Model
{
    use HasFactory;

    protected $table = 'dpa';
    // Primary key is 'id', which is Laravel's default. No need to specify.
    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_user_dosen_pembimbing',
    ];

    /**
     * Get the mahasiswa that owns the DPA record.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get the dosen pembimbing (user) for this DPA record.
     */
    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_dosen_pembimbing', 'id_user');
    }
}
