<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    protected $table = "businesses";
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id', 'sequence_number', 'business_identification_number', 'line_of_business', 'business_name', 'owner',
        'address', 'address1', 'barangay', 'code', 'stat',
        'gross_receipts', 'capital', 'permit_number', 'business_tax', 'fees',
        'surcharge', 'total', 'official_receipt_number', 'official_receipt_date', 'terms',
        'tax_year', 'qtr_paid', 'contact_number', 'number_of_employees', 'status',
        'first_name',
        'middle_name',
        'last_name',
        'strategic_location',
        'description',
    ];
}
