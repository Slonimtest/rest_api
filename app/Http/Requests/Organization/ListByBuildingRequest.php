<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Request;

class ListByBuildingRequest extends Request
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
            'buildingId' => [
                'required',
                'integer',
                'exists:buildings,id'
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'buildingId' => $this->route('buildingId')
        ]);
    }
}
