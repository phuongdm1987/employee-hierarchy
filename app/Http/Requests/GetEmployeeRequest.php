<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level' => 'nullable|min:1|integer',
        ];
    }

    function getLevel() : int|null {
        $level = $this->get('level');

        return $level === null ? null : (int)$level;
    }
}
