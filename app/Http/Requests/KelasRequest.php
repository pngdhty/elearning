<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kelas' => 'required|integer',
            'kode_kelas' => 'required|string',
            'guru_id' => 'required|integer|exists:users,id|unique:kelas,guru_id,'. $this->kelas
        ];
    }

    public function attributes()
    {
        return [
           'kelas' => 'Kelas',
           'kode_kelas' => 'Kode Kelas',
           'guru_id' => 'Wali Kelas'
        ];
    }
}
