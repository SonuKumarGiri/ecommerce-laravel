<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderConfirmedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Order ' . $this->order->order_number . ' Confirmed';
        $total = number_format($this->order->total_amount, 2);

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your order. We have successfully received it.')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Total Amount: ₹' . $total)
            ->action('View Order', url('/my-orders'))
            ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->order->total_amount,
            'message' => 'Order ' . $this->order->order_number . ' confirmed.'
        ];
    }
}
