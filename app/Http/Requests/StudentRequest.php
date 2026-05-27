<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'matricul' => 'required|string|size:9',
            'first' => 'required|string',
            'last' => 'required|string',
            'genre' => 'required|string',
            'date' => 'required|date',
            'lieu' => 'required|string',
            'nation' => 'required|string',
            'residence' => 'required|string',
            'type' => 'required|string',
            'civilit' => 'required|string',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephon' => 'required|string|size:10',
            'email' => 'nullable|email',
            'status' => 'nullable|string',
        ];
    }
}
