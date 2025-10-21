<?php

namespace App\Notifications;

use App\Models\PaymentSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceiptEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PaymentSchedule $schedule,
        public string $type = 'receipt' // 'receipt', 'payment_confirmation'
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
                ->subject('Payment Receipt')
                ->line('Please contact the school for your receipt details.');
        }

        $description = $this->schedule->description ?? 'Payment';
        $amount = $this->schedule->amount_due ?? 0;
        $dueDate = $this->schedule->due_date ?? now();

        $mail = (new MailMessage)
            ->subject('Payment Receipt - ' . $description)
            ->line('Thank you for your payment! Here is your receipt for your records.')
            ->line('')
            ->line("**Payment Details:**")
            ->line("**Amount Paid:** ₱" . number_format($amount, 2))
            ->line("**Payment Date:** " . $dueDate->format('M d, Y'))
            ->line("**Description:** " . $description)
            ->line('')
            ->line('Please find your detailed receipt attached below.')
            ->line('')
            ->line('Thank you for your payment!');

        // Add download link - use different routes for one-time vs installment payments
        if ($this->schedule->payment_plan_id === null) {
            // One-time payment - use one-time receipt route
            $receiptUrl = route('invoice.onetime.receipt', [
                'invoice' => $this->schedule->invoice_id
            ]);
        } else {
            // Installment payment - use schedule receipt route
            $receiptUrl = route('invoice.schedule.receipt', [
                'invoice' => $this->schedule->invoice_id,
                'schedule' => $this->schedule->id
            ]);
        }
        
        $mail->action('Download Receipt', $receiptUrl);

        return $mail;
    }
}
