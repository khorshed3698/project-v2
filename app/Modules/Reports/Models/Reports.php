<?php namespace App\Modules\Reports\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reports extends Model {

	protected $table = 'custom_reports';


    protected $fillable = ['report_title','selection_type', 'report_para1','status','is_column_text_full','user_id'];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
//            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    public static function isReportAdmin()
    {
        return ['1x101', '15x151'];
    }
}
