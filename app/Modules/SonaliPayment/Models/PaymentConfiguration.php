<?php
namespace App\Modules\SonaliPayment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentConfiguration extends Model {

    protected $table = 'sp_payment_configuration';
    protected $fillable = array(
        'id',
        'process_type_id',
        'payment_category_id',
        'amount',
        'vat_on_transaction_charge_percent',
        'trans_charge_percent',
        'trans_charge_min_amount',
        'trans_charge_max_amount',
        'status',
        'created_by',
        'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    /*     * ******************End of Model Class***************** */
}
