<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false; // Note: 'waktu_kirim' is handled manually or by DB default.

    protected $fillable = [
        'id_mahasiswa',
        'id_user',
        'jenis_kirim',
        'waktu_kirim',
        'isi_pesan',
        'status_kirim',
    ];

    /**
     * Get the mahasiswa who received the notifikasi.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Get the user who sent the notifikasi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
// Note: The 'jenis_kirim' field is assumed to be a string or enum type that indicates the type of notification (e.g., email, SMS).
// The 'status_kirim' field is assumed to be a boolean or integer indicating whether the notification was sent successfully (1 for sent, 0 for not sent).
// The 'waktu_kirim' field is assumed to be a timestamp indicating when the notification was sent. If your database handles timestamps automatically, you can remove the manual handling in your application logic.
// The model uses the HasFactory trait for factory-based testing and seeding.
// The 'id_notifikasi' is the primary key, and the model does not use timestamps for creation and update times, as the 'waktu_kirim' is handled separately.
// The 'isi_pesan' field stores the content of the notification message.
// This model represents the 'notifikasi' table, which stores notifications sent to students.
// It includes relationships to the Mahasiswa and User models, allowing access to the student who received  the notification and the user who sent it.