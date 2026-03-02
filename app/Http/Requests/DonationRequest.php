<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount'            => ['required', 'numeric', 'min:1', 'max:10000'],
            'type'              => ['required', 'in:one_time,recurring'],
            'payment_method_id' => ['required', 'string', 'starts_with:pm_'],
            'donor_name'        => ['nullable', 'string', 'max:100'],
        ];
    }
}
