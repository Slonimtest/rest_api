<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SearchByActivityNameRequest extends Request
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_activity' => [
                'required',
                'string',
                Rule::exists('activities', 'name')
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'name_activity' => $this->query('name_activity')
        ]);
    }
}
