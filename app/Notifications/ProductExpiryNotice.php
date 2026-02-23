<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Product;

class ProductExpiryNotice extends Notification
{
    use Queueable;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    // Notification channel
    public function via($notifiable)
    {
        return ['database']; // Email + Database
    }

    // Database message
    public function toDatabase($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'title' => $this->product->title,
            'expires_at' => $this->product->expires_at,
            'message' => "Your product \"{$this->product->title}\" will expire soon."
        ];
    }
}