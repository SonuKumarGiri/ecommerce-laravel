<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Order;
use App\Notifications\OrderConfirmedNotification;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = $this->order->user;
            
            if ($user) {
                $user->notify(new OrderConfirmedNotification($this->order));
                Log::info('Order confirmation notification sent', ['order_id' => $this->order->id]);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send order confirmation', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
