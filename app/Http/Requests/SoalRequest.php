<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoalRequest extends FormRequest
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
            'judul' => 'required|string',
            'mapel_id' => 'sometimes|required|integer|exists:mapels,id',
            'kelas' => 'sometimes|required|integer|exists:kelas,kelas',
            'kelas_materi' => 'sometimes|required',
            'materi_id' => 'sometimes|required|integer|exists:materis,id',
            'kategori' => 'required|string|in:Harian,UAS,UTS,Quiz'
        ];
    }

    public function attributes()
    {
        return [
            'judul' => 'Judul',
            'mapel_id' => 'Mata Pelajaran',
            'kelas' => 'Kelas',
            'kelas_materi' => 'Kelas',
            'materi_id' => 'Materi',
            'kategori' => 'Kategori'
        ];
    }
}
