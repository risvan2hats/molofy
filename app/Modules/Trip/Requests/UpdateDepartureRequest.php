<?php

namespace App\Modules\Trip\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => [
                'required',
                'exists:trips,id',
            ],
            'boat_id' => [
                'required',
                'exists:boats,id',
            ],
            'departure_at' => [
                'required',
                'date',
            ],
            'arrival_at' => [
                'nullable',
                'date',
                'after:departure_at',
            ],
            'meeting_point' => [
                'required',
                'string',
                'max:255',
            ],
            'meeting_instructions' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                Rule::in(['scheduled', 'cancelled', 'completed']),
            ],
            'operator_ids' => ['nullable', 'array'],
            'operator_ids.*' => ['integer', 'exists:operators,id'],
        ];
    }
}
