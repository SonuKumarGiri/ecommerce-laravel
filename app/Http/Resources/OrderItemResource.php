<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'price' => round((float) $this->price, 2),
            'quantity' => (int) $this->quantity,
            'subtotal' => round((float) ($this->total ?? ($this->price * $this->quantity)), 2),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
