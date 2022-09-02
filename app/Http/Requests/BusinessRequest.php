<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
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
            'sequence_number' => ['nullable'],
            'business_identification_number' => ['nullable'],
            'line_of_business' => ['required'],
            'business_name' => ['required'],
            'owner' => ['required'],
            'address' => ['required'],
            'address1' => ['nullable'],
            'barangay' => ['required'],
            'code' => ['nullable'],
            'stat' => ['nullable'],
            'gross_receipts' => ['nullable'],
            'capital' => ['nullable'],
            'permit_number' => ['nullable'],
            'business_tax' => ['nullable'],
            'fees' => ['nullable'],
            'surcharge' => ['nullable'],
            'total' => ['nullable'],
            'official_receipt_number' => ['nullable'],
            'official_receipt_date' => ['nullable'],
            'terms' => ['nullable'],
            'tax_year' => ['nullable'],
            'qtr_paid' => ['nullable'],
            'contact_number' => ['required'],
            'number_of_employees' => ['nullable'],
            'status' => ['nullable'],
            'first_name' => ['nullable'],
            'middle_name' => ['nullable'],
            'last_name' => ['nullable'],
            'strategic_location' => ['nullable'],
            'description' => ['nullable'],
        ];
    }
}
