<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $accountHeadId = $this->route('id');
        $rules = [
            'name' => [
                'required',
                Rule::unique('account_heads', 'name')->ignore($accountHeadId),
            ],
            'group_id' => 'required'
        ];
        return $rules;
    }
}
