<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationUpdateRequest extends FormRequest
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
            'application_number'  => ['required', 'integer'],
            'patient_name'        => ['sometimes','string','max:255'],
            'patient_birth_year'  => ['sometimes','integer','digits:4','min:1900','max:' . now()->year],
            'institution_id'      => ['sometimes','exists:medical_institutions,id'],
            'from_institution_id' => ['sometimes','nullable','exists:medical_institutions,id'],
            'specialist_id'       => ['sometimes','nullable','exists:specialists,id'],
            'diagnosis_id'        => ['sometimes','exists:diagnoses,id'],
            'consultation_date'   => ['sometimes','date_format:d.m.Y'],
            'created_at'          => ['sometimes','date_format:d.m.Y'],
        ];
    }
}
