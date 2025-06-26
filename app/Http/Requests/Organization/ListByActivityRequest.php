<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Request;

class ListByActivityRequest extends Request
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
            'activityId' => [
                'required',
                'integer',
                'exists:activities,id'
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'activityId' => $this->route('activityId')
        ]);
    }
}
