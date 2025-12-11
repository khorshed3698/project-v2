<?php


namespace App\Modules\MutationLand\Models;


use Illuminate\Database\Eloquent\Model;

class MutationLandPayment extends Model
{
    protected $table = 'mutation_land_payment';
    protected $fillable = [
        'id',
        'ml_id',
        'challan_no',
        'transaction_id',
        'transaction_amount',
        'transaction_date',
        'request',
        'response',
        'status',
        'created_at',
        'updated_at'
    ];
}