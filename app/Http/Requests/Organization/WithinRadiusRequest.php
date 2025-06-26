<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WithinRadiusRequest extends Request
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
            'lat' => [
                'required',
                'numeric',
                'between:-90,90'
            ],
            'lng' => [
                'required',
                'numeric',
                'between:-180,180'
            ],
            'radius' => [
                'required',
                'numeric',
                'min:0.1',
                'max:10000'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'lat.required' => 'Широта (lat) обязательна',
            'lat.between' => 'Широта должна быть между -90 и 90 градусами',
            'lng.required' => 'Долгота (lng) обязательна',
            'lng.between' => 'Долгота должна быть между -180 и 180 градусами',
            'radius.required' => 'Радиус поиска обязателен',
            'radius.min' => 'Радиус должен быть не менее 0.1 км',
            'radius.max' => 'Максимальный радиус поиска - 100 км'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'lat' => (float) $this->query('lat'),
            'lng' => (float) $this->query('lng'),
            'radius' => (float) $this->query('radius', 10)
        ]);
    }
}
