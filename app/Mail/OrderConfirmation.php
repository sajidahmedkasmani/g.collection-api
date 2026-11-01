<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\EmailLog;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmed - #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    /**
     * Jab email successfully send ho jaye (Queue Worker ke zariye)
     */
    public function sent($message)
    {
        // Email Log save karein
        EmailLog::create([
            'recipient_email' => $this->order->email ?? $this->order->customer_email,
            'subject'         => 'Order Confirmed - #' . $this->order->order_number,
            'status'          => 'sent',
            'error_message'   => null,
        ]);
    }

    /**
     * Jab email send fail ho jaye
     */
    public function failed(\Throwable $exception)
    {
        // Failed Email Log save karein
        EmailLog::create([
            'recipient_email' => $this->order->email ?? $this->order->customer_email,
            'subject'         => 'Order Confirmed - #' . $this->order->order_number,
            'status'          => 'failed',
            'error_message'   => $exception->getMessage(),
        ]);
    }
}