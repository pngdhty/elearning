<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuruRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:1'],
            // Register
            'nip' => 'required|integer|unique:gurus,nip',
            'no_telp' => 'required|min:10|max:14|unique:gurus,no_telp',
            'agama' => 'required|string|in:Islam,Kristen Protestan,Katolik,Hindu,Buddha,Konghucu',
            'jenkel' => 'required|string|in:Laki-Laki,Perempuan',
            'dob' => 'required|date|before:2000-12-31',
            'alamat' => 'required',
            'foto' => 'nullable|image|max:1024',
            'pendidikan' => 'string'
        ];
    }

    public function attributes()
    {
        return [
            'nama' => 'Nama',
            'email' => 'Email',
            'role' => 'Role',
            'nip' => 'NIP',
            'no_telp' => 'Nomor Handphone',
            'agama' => 'Agama',
            'jenkel' => 'Jenis Kelamin',
            'dob' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'foto' => 'Foto',
            'pendidikan' => 'Gelar Pendidikan'
        ];
    }
}
