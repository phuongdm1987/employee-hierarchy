<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            '*' => 'required|array',
            '*.0' => 'required|string|max:255',
            '*.1' => 'required|string|max:255',
        ];
    }

    function getValidatedData(): array
    {
        return array_map(function ($item) {
            return ['name' => $item[0], 'supervisor' => $item[1]];
        }, $this->validated());
    }
}
