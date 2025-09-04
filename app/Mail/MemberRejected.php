<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $namaUser, public string $alasan) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Pendaftaran Anggota Ditolak');
    }

    public function build()
    {
        return $this->markdown('emails.member-rejected');
    }
}
