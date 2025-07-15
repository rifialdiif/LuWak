<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class IntervensiOrtuMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pesan;
    public $namaMahasiswa;
    public $nim;
    public $prodi;

    /**
     * Create a new message instance.
     *
     * @param string $pesan
     * @param string $namaMahasiswa
     * @param string $nim
     * @param string $prodi
     */
    public function __construct($pesan, $namaMahasiswa, $nim, $prodi)
    {
        $this->pesan = $pesan;
        $this->namaMahasiswa = $namaMahasiswa;
        $this->nim = $nim;
        $this->prodi = $prodi;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Informasi Akademik Politeknik Enjinering Indorama untuk Orang Tua/Wali ' . $this->namaMahasiswa . ' - ' . $this->nim,
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.email')
            ->with([
                'pesan' => $this->pesan,
                'prodi' => $this->prodi,
            ]);
    }
}
