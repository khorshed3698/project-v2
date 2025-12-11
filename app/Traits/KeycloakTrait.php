<?php

namespace App\Traits;

use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Session;
use LaravelKeycloakAdmin\Facades\KeycloakAdmin;
use Exception;

trait KeycloakTrait
{
    public static function kcStoreNewUser($user, $new_password)
    {
        KeycloakAdmin::user()->create([
            'body' => [
                'username' => $user->user_email,
                'enabled' => true,
                'emailVerified' => true,
                'email' => $user->user_email,
                'credentials' => [[
                    'type' => 'password',
                    'value' => $new_password,
                    'temporary' => false
                ]]
            ]
        ]);
    }

    public static function kcStoreSignUpUser($user)
    {
        KeycloakAdmin::user()->create([
            'body' => [
                'username' => $user->user_name_type === 'email' ? $user->user_email : $user->user_mobile,
                'enabled' => true,
                'emailVerified' => true,
                'email' => $user->user_email ? $user->user_email : '',
                'credentials' => [[
                    'type' => 'password',
                    'value' => $user->password,
                    'temporary' => false
                ]]
            ]
        ]);
    }

    

    public function kcLogoutWithoutApp($idToken)
    {
        $userLogoutEndpoint = config('app.KEYCLOAK_USER_LOGOUT_ENDPOINT');

        $ch = curl_init($userLogoutEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'id_token_hint=' . $idToken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);
    }

    public function kcCheckUser($userEmail)
    {
        $kcUser = KeycloakAdmin::user()->find([ //This query returns an array if any user is found, else it returns true.
            'query' => [
                'email' => $userEmail
            ]
        ]);

        return is_array($kcUser);
    }

    public function kcCheckUserByUserName($username)
    {
        $kcUser = KeycloakAdmin::user()->find([ //This query returns an array if any user is found, else it returns true.
            'query' => [
                'username' => $username
            ]
        ]);

        return is_array($kcUser);
    }

    public static function kcChangePassword($email, $newPassword)
    {
        try {
            $kcUser = KeycloakAdmin::user()->find([
                'query' => [
                    'email' => $email
                ]
            ]);

            keycloakAdmin::user()->update([
                'id' => $kcUser[0]['id'],
                'body' => [
                    'credentials' => [[
                        'type' => 'password',
                        'value' => $newPassword,
                        'temporary' => false
                    ]]
                ]
            ]);
            return true;
        } catch (Exception $e) {
            return  CommonFunction::showErrorPublic($e->getMessage()) . "[KCT-1001]";
        }
    }

    public static function kcChangePasswordNew($user, $newPassword)
    {
        try {
            if (empty($user->user_email)) {
                $kcUser = KeycloakAdmin::user()->find([
                    'query' => [
                        'username' => $user->username
                    ]
                ]);
            } else {
                $kcUser = KeycloakAdmin::user()->find([
                    'query' => [
                        'email' => $user->user_email 
                    ]
                ]);
            }

            keycloakAdmin::user()->update([
                'id' => $kcUser[0]['id'],
                'body' => [
                    'credentials' => [[
                        'type' => 'password',
                        'value' => $newPassword,
                        'temporary' => false
                    ]]
                ]
            ]);
            return true;
        } catch (Exception $e) {
            return  CommonFunction::showErrorPublic($e->getMessage()) . "[KCT-1001]";
        }
    }

    public static function userBulkUpload($user)
    {
        try {
            $kcUserWithName = KeycloakAdmin::user()->find([
                'query' => [
                    'username' => $user->user_email
                ]
            ]);

            if (!is_array($kcUserWithName)) {
                KeycloakAdmin::user()->create([
                    'body' => [
                        'username' => $user->user_email,
                        'enabled' => true,
                        'emailVerified' => true,
                        'email' => trim($user->user_email),
                        'credentials' => [[
                            'type' => 'password',
                            'value' => '123456a@',
                            'temporary' => false
                        ]]
                    ]
                ]);
            }

            /*
             *  This is for update existing user & create new user
             *
            if (is_array($kcUserWithName)) {
                keycloakAdmin::user()->update([
                    'id' => $kcUserWithName[0]['id'],
                    'email' => trim($user->user_email),
                    'body' => [
                        'credentials' => [[
                            'type' => 'password',
                            'value' => '123456a@',
                            'temporary' => false
                        ]]
                    ]
                ]);
            } else {
                KeycloakAdmin::user()->create([
                    'body' => [
                        'username' => $user->user_email,
                        'enabled' => true,
                        'emailVerified' => true,
                        'email' => trim($user->user_email),
                        'credentials' => [[
                            'type' => 'password',
                            'value' => '123456a@',
                            'temporary' => false
                        ]]
                    ]
                ]);
            }
            */
        } catch (Exception $e) {
            return CommonFunction::showErrorPublic($e->getMessage()) . "[KCT-1002]";
        }
    }
}
