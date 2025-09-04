<?php

namespace App\Mail;

use App\Models\Peminjaman_alat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengingatKeterlambatanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman; // Properti ini akan membawa data ke view

    /**
     * Create a new message instance.
     */
    public function __construct(Peminjaman_alat $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Keterlambatan Pengembalian Alat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Kita akan membuat view ini di langkah berikutnya
        return new Content(
            view: 'emails.pengingat_keterlambatan',
        );
    }
}
