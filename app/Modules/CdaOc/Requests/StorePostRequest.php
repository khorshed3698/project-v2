<?php

namespace App\Modules\CdaOc\Requests;

use App\Http\Requests\Request;

class StorePostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //dd(3);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //dd(1);
        return [

        ];
    }

    /**
    * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        //dd(2);
        return [

        ];
    }

}