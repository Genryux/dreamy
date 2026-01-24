<?php

namespace App\Mail;

use App\Models\Applicants;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantArchivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Applicants $applicant,
        public ?string $reason = null
    ) {}

    public function build()
    {
        $applicantName = $this->applicant->first_name . ' ' . $this->applicant->last_name;
        $loginUrl = url('/admission/dashboard');
        
        // Build the email body
        $bodyText = "We regret to inform you that your application has been archived.\n\n";
        
        if ($this->reason) {
            $bodyText .= "Reason: " . $this->reason . "\n\n";
        }
        
        $bodyText .= "If you believe this is an error or have any questions, please contact our Admissions Office for assistance.\n\n";
        $bodyText .= "You can still access your account to view your application details.";

        return $this->subject('Application Archived')
            ->view('emails.applicant-accepted')
            ->with([
                'applicantName' => $applicantName,
                'title' => 'Application Archived',
                'bodyText' => $bodyText,
                'loginUrl' => $loginUrl,
            ]);
    }
}
