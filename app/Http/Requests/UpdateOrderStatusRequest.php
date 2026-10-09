<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:PLACED,CONFIRMED,PROCESSING,SHIPPED,DELIVERED,CANCELLED',
            'payment_status' => 'required|string|in:PENDING,SUCCESS,FAILED,REFUNDED',
        ];
    }
}
