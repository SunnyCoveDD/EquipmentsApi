<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'equipment_type_id' => 'required',
            'serial_number' => 'required|unique:equipments,serial_number,' . $this->id,
            'description' => 'required',
        ];
    }
    public function messages()
    {
        return[
            'required' => 'Это поле обязательно для заполения',
            'unique' => 'Этот серийный номер уже используется',
        ];
    }
    public function authorize()
    {
        return true;
    }
}
