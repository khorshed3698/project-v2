<?php

namespace App\Modules\CompanyAssociation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyAssociation extends Model
{

    protected $table = 'company_association_request';
    protected $fillable = [
        'id',
        'company_type',
        'user_id',
        'company_name_en',
        'company_name_bn',
        'current_company_ids',
        'requested_company_id',
        'approved_user_type',
        'desk_remarks',
        'user_remarks',
        'application_date',
        'status_id',
        'status',
        'is_archive',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    public static function companyAssociationList()
    {
        $userType = CommonFunction::getUserType();


        DB::statement(DB::raw('set @rownum = 0'));
        $data = CompanyAssociation::leftJoin('company_info', 'company_info.id', '=', 'company_association_request.requested_company_id')
            ->leftJoin('users', 'users.id', '=', 'company_association_request.user_id')
            ->where(function ($query) use ($userType) {
                $query->where('company_association_request.is_archive', 0);
                if ($userType == '5x505') {
                    $query->where('company_association_request.user_id', Auth::user()->id);
                }
            })
            ->orderBy('company_association_request.application_date', 'DESC')
            ->get([DB::raw('@rownum := @rownum + 1 AS sl'), 'company_association_request.*', 'company_info.company_name', 'users.user_email']);

        return $data;
    }

}
