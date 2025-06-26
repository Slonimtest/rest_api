<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SearchByNameRequest extends Request
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
            'name_organization' => [
                'required',
                'string',
                Rule::exists('organizations', 'name')
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'name_organization' => $this->query('name_organization')
        ]);
    }
}
