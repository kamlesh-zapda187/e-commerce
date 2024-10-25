<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_type',
        'order_no',
        'buyer_id',
        'sub_total',
        'tax',
        'tax_amount',
        'shipping_charge',
        'total_amount',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_name',
        'shipping_email',
        'shipping_contact',
        'shipping_address',
        'shipping_address2',
        'shipping_zipcode',
        'shipping_country',
        'billing_country',
        'additional_information',
        'rejection_note',
        'order_status',
        'payment_method',
        'payment_intent_id',
        'payment_status',
        'payment_date',
        'promo_code'
    ];
}
