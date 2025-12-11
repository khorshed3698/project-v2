<?php

namespace App\Providers;

use App\Modules\Settings\Models\Configuration;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->isSecure()) {
            \URL::forceSchema('https');
        }

        /*
        *   $attribute: The name of the field being validated.
        *   $value: The value of the field being validated.
        *   $parameters: An array of additional parameters passed to the rule. In this case, the custom rule expects two parameters: the name of another field ($otherField) and a value ($otherValue). The rule will only apply if the value of $otherField is not equal to $otherValue.
        *   $validator: An instance of the Illuminate\Validation\Validator class, which can be used to access the data being validated and perform additional validation rules.
        */

        $this->app['validator']->extend('required_unless', function($attribute, $value, $parameters, $validator) {
            $otherField = $parameters[0];
            $otherValue = $parameters[1];

            if ($validator->getData()[$otherField] != $otherValue) {
                return true;
            }

            return !empty($value);
        });

        $this->app['validator']->extend('bd_phone', function ($attribute, $value, $parameters) {
            if (!preg_match('/^[+]{0,1}(88-)?[0-9]{4,12}$/', $value)) {
                return false;
            }
            return true;
        });

        $this->app['validator']->extend('bd_nid', function ($attribute, $value, $parameters) {
            $length = strlen($value);
            if (in_array($length,[10,13,17])) {
                return true;
            }
            return false;
        });

        $this->app['validator']->extend('image300x80',function($attribute,$value,$parameters){
            $image = getimagesize($value);
            $width = $image[0];
            $height = $image[1];
            if($height == 80 && $width == 300){
                return true;
            }
            return false;
        });
        $this->app['validator']->extend('image300x300',function($attribute,$value,$parameters){
            $image = getimagesize($value);
            $width = $image[0];
            $height = $image[1];
            if($height == 300 && $width == 300){
                return true;
            }
            return false;
        });


        $this->app['validator']->extend('alphaSpace', function ($attribute, $value, $parameters) {
            // echo '<pre>';print_r($value);exit;
            if (!preg_match('/^[a-zA-Z ]*$/', $value)) {
                return false;
            }
            return true;
        });

        $this->app['validator']->extend('alphaComma',function($attribute,$value,$parameters){
            if(preg_match("/^([A-Z],)+[A-Z]$/", $value) == 1) {  // allow starting with A-Z then comma then any repeated with A-Z then comma, and ending with A-Z
                return true;
            }
            return false;

        });

        $this->app['validator']->extend('alphaSpaceArray', function ($attribute, $value, $parameters) {
            foreach ($value as $v) {
                if (!preg_match('/^[a-zA-Z ]*$/', $v)) {
                    return false;
                }
            }
            return true;
        });

        $this->app['validator']->extend('numberDotArray', function ($attribute, $value, $parameters) {
            foreach ($value as $v) {
                if (!preg_match('/^\d*(?:\.{1}\d+)?$/', $v)) {
                    return false;
                }
            }
            return true;
        });

        $this->app['validator']->extend('numericArray', function ($attribute, $value, $parameters)
        {
            foreach ($value as $v) {
                if (!is_int($v)) {
                    return false;
                }
            }
            return true;
        });

        $this->app['validator']->extend('requiredArray', function ($attribute, $value, $parameters)
        {
            foreach ($value as $v) {
                if(empty($v)){
                    return false;
                }
            }
            return true;
        });


        $this->app['validator']->extend('bd_mobile', function ($attribute, $value, $parameters) {
            $first_two_digit = substr($value, 0, 2);
            $first_four_digit = substr($value, 0, 4);
            $first_five_digit = substr($value, 0, 5);
            $first_six_digit = substr($value, 0, 6);

            $length = strlen($value);
            if ($length == 11) {
                if (is_numeric($value) && $first_two_digit == '01') {
                    return true;
                }
            } elseif ($length == 13) {
                if (is_numeric($value) && $first_four_digit == '8801') {
                    return true;
                }
            } elseif ($length == 14) {
                if ($first_five_digit == '+8801') {
                    return true;
                }
            } elseif ($length == 15) {
                if (is_numeric($value) && $first_six_digit == '008801') {
                    return true;
                }
            }
            return false;
        });

        /*
         * Global passport Validation
         * Bangladesh format    :   AB1234567 (2 Character, 7 Digit)
         * India format         :   J1236547 (1 Character, 7 Digit)
         * China format         :   G05473471 (1 Character, 8 Digit)
         * Russia format        :   123456789 (9 Digit)
         * USA format           :   123654789 (Most of passport 9 Digit)
         * Australian format    :   M1234567 (1 Character, 7 Digit)
         * ^(([A-Z]{1}|[A-Z]{2})(\d{7}|\d{8}))$|^(\d{9})$
         */
        $this->app['validator']->extend('passport', function ($attribute, $value, $parameters) {
//            if (!preg_match('/^([A-Za-z]{1}|[A-Za-z]{2}|[A-Za-z]{3})*(\d{6}|\d{7}|\d{8})+$/', $value)) {
//                return false;
//            }
//            return true;

            if (!preg_match('/^[a-zA-Z 0-9]{7,12}$/', $value)) {
                return false;
            }
            return true;
        });

        // Bangladesh passport format
        $this->app['validator']->extend('passport_bd', function ($attribute, $value, $parameters) {
            if (!preg_match('/^([A-Za-z]{2})*(\d{7})+$/', $value)) {
                return false;
            }
            return true;
        });

        // valid format example: [0123456789123, 01234567890123456]
        // Global Phone or mobile number format
        $this->app['validator']->extend('phone_or_mobile', function ($attribute, $value, $parameters) {
            if (!preg_match('/^[+]{0,1}[\s()-]*([0-9][\s()-]*){6,20}$/', $value)) {
                return false;
            }
            return true;
        });

        view()->composer('ProcessPath::batch-process', function ($view) {
            $smart_remarks_conf = Configuration::where('caption','SMART_REMARKS_SWITCH')->first(['value', 'value2']);
            $smart_remarks_switch = $smart_remarks_conf->value; // plug 0 = off, 1 = on
            $smart_remarks_process = explode(",", $smart_remarks_conf->value2); // process type id

            $view->with('smart_remarks_switch', $smart_remarks_switch);
            $view->with('smart_remarks_process', $smart_remarks_process);
        });
    }

    public function isSecure()
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }

        return $isSecure;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
