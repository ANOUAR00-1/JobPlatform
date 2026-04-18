<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntretienConvocationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $interviewDate;
    public $interviewTime;
    public $location;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle, $interviewDate, $interviewTime, $location)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->interviewDate = $interviewDate;
        $this->interviewTime = $interviewTime;
        $this->location = $location;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation à un entretien - ' . $this->jobTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.entretien_convocation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
