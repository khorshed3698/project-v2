<?php

namespace App\Modules\SecurityClearance;

use Illuminate\Support\Facades\Auth;

class SecurityClearanceACL
{
    const USER_TYPE_ADMIN = '1x101';
    const USER_TYPE_SYETEM_ADMIN = '1x102';
    const USER_TYPE_DESK = '4x404';
    const USER_TYPE_IT_HELP_DESK = '2x202';

    public static function getAccessRight($right)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $userType = $user->user_type;

        $accessRights = [
            self::USER_TYPE_ADMIN => 'V',
            self::USER_TYPE_SYETEM_ADMIN => 'V',
            self::USER_TYPE_DESK => 'AVE',
            self::USER_TYPE_IT_HELP_DESK => 'AVE'
        ];

        $accessRight = isset($accessRights[$userType]) ? $accessRights[$userType] : '';
        if ($right !== '' && strpos($accessRight, $right) !== false) {
            return true;
        }

        return false;
    }
}