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
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'subtotal' => (float) ($this->price * $this->quantity),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
