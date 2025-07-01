<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediksi extends Model
{
    use HasFactory;

    protected $table = 'prediksi';
    protected $primaryKey = 'id_prediksi';
    public $timestamps = false; // Note: 'tanggal_prediksi' is handled manually or by DB default.

    protected $fillable = [
        'id_mahasiswa',
        'id_user',
        'tanggal_prediksi',
        'hasil_prediksi',
        'confidence_score',
    ];

    /**
     * Get the mahasiswa associated with the prediksi.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get the user who generated the prediksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
// End of file: app/Models/Prediksi.php
// This model represents the 'prediksi' table, which stores prediction results for students.
// It includes relationships to the Mahasiswa and User models, allowing access to the student and the user who made the prediction.
// The 'tanggal_prediksi' field is expected to be managed either by the application logic or by the database default value.
// The model uses the HasFactory trait for factory-based testing and seeding.
// The 'id_prediksi' is the primary key, and the model does not use timestamps for creation and update times, as the 'tanggal_prediksi' is handled separately.
// The 'hasil_prediksi' field stores the prediction result, and 'confidence_score' indicates the confidence level of the prediction.    