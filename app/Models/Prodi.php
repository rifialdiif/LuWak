<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    public $timestamps = false;

    protected $fillable = [
        'nama_prodi',
    ];

    /**
     * Get all of the users for the Prodi.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_prodi', 'id_prodi');
    }
}
