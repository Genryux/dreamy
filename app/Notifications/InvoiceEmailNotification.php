<?php

namespace App\Notifications;

use App\Models\PaymentSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PaymentSchedule $schedule,
        public string $type = 'invoice' // 'invoice', 'reminder', 'due', 'overdue'
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Safety check - if schedule is null, create a basic email
        if (!$this->schedule) {
            return (new MailMessage)
                ->subject('Invoice Notification')
                ->line('Please contact the school for your invoice details.');
        }

        $description = $this->schedule->description ?? 'Payment';
        $amount = $this->schedule->amount_due ?? 0;
        $dueDate = $this->schedule->due_date ?? now();

        $subject = $this->getSubject($description);
        $message = $this->getMessage();

        $mail = (new MailMessage)
            ->subject($subject)
            ->line($message)
            ->line("**Amount Due:** ₱" . number_format($amount, 2))
            ->line("**Due Date:** " . $dueDate->format('M d, Y'))
            ->line('')
            ->line('Here is your invoice for ' . $description . '. You can download your detailed invoice using the link below.')
            ->line('')
            ->line('Please review the invoice details and make your payment by the due date to avoid any late fees.')
            ->line('')
            ->line('Thank you for your attention to this matter.');

        // Add download link - use different routes for one-time vs installment payments
        if ($this->schedule->payment_plan_id === null) {
            // One-time payment - use one-time route
            $invoiceUrl = route('invoice.onetime.download', [
                'invoice' => $this->schedule->invoice_id
            ]);
        } else {
            // Installment payment - use schedule route
            $invoiceUrl = route('invoice.schedule.download', [
                'invoice' => $this->schedule->invoice_id,
                'schedule' => $this->schedule->id
            ]);
        }
        
        $mail->action('Download Invoice', $invoiceUrl);

        return $mail;
    }

    private function getSubject(string $description): string
    {
        return match($this->type) {
            'reminder' => "Reminder: Payment Due in 5 Days - " . $description,
            'due' => "Payment Due Today - " . $description,
            'overdue' => "OVERDUE: Payment Required - " . $description,
            default => "Invoice: " . $description
        };
    }

    private function getMessage(): string
    {
        return match($this->type) {
            'reminder' => "This is a friendly reminder that your payment will be due in 5 days. Please prepare for your upcoming payment.",
            'due' => "Your payment is due today. Please make your payment as soon as possible to avoid any late fees.",
            'overdue' => "Your payment is now overdue. Please make immediate payment to avoid additional late fees and penalties.",
            default => "Here is your invoice for your school fees. Please review the details and make your payment by the due date."
        };
    }
}
