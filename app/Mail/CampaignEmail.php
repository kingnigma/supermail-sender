<?php

namespace App\Mail;

use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignEmail extends Mailable implements ShouldQueue
{
    use SmtpSettings,Queueable, SerializesModels;

    public $subject;
    public $message;
    public $attachmentArray;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message, $attachmentArray = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->attachmentArray = $attachmentArray;

    }

    public function build()
    {
        $this->setMailConfigs();

        $email = $this->subject($this->subject)
            ->view('emails.campaign')
            ->with(['content' => $this->message]);

        // Add attachments
        // foreach ($this->attachments as $attachment) {
        //     if (is_array($attachment)) {
        //         // Handle array format with options
        //         $email->attach($attachment['file'], $attachment['options']);
        //     } else {
        //         // Handle simple path string
        //         $email->attach($attachment);
        //     }
        // }

        return $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.campaign',
            with: [
                'content' => $this->message,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     $mailAttachments = [];

    //     foreach ($this->attachments as $attachment) {
    //         $mailAttachments[] = Attachment::fromPath($attachment);
    //     }

    //     return $mailAttachments;
    // }

    public function attachments(): array
    {
        $mailAttachments = [];

        foreach ($this->attachmentArray as $attachment) {
            if (is_array($attachment)) {
                $mailAttachments[] =  Attachment::fromPath($attachment['file'])->withMime($attachment['options']['mime'])
                    ->as($attachment['options']['as']);
            } else {
                $mailAttachments[] = Attachment::fromPath($attachment);
            }
        }

        return $mailAttachments;
    }
}
