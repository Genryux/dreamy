<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantProgressMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $applicantName,
        public string $title,
        public string $bodyText,
        public ?string $loginUrl = null
    ) {}

    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.applicant-accepted')
            ->with([
                'applicantName' => $this->applicantName,
                'title' => $this->title,
                'bodyText' => $this->bodyText,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}


