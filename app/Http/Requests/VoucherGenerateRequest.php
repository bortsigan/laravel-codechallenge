<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherGenerateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->user()->vouchers()->count() >= 10) {
                $validator->errors()->add('limit', 'You have reached the maximum limit of 10 vouchers.');
            }
        });
    }
}
