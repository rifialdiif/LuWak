<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'role',
        'nip_nim',
        'id_prodi',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the prodi that owns the User.
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    /**
     * Get the mahasiswa record associated with the user.
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
    }

    /**
     * Get all of the dpa records for the User (if the user is a Dosen).
     */
    public function dpaRecords(): HasMany
    {
        return $this->hasMany(Dpa::class, 'id_user_dosen_pembimbing', 'id_user');
    }

    /**
     * Get all of the prediksi records created by the User.
     */
    public function prediksi(): HasMany
    {
        return $this->hasMany(Prediksi::class, 'id_user', 'id_user');
    }

    /**
     * Get all of the notifikasi records sent by the User.
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }
}
