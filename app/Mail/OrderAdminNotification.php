<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛒 Nouvelle commande ' . $this->order->order_number
                . ' — ' . $this->order->first_name . ' ' . $this->order->last_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order.admin-notification',
        );
    }
}
