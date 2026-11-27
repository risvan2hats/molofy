<?php

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adults' => ['required', 'integer', 'min:0'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],

            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $totalGuests = (int) $this->input('adults', 0)
                + (int) $this->input('children', 0)
                + (int) $this->input('infants', 0);

            if ($totalGuests < 1) {
                $validator->errors()->add(
                    'adults',
                    'Please select at least one guest.'
                );
            }
        });
    }
}
