<?php

namespace App\Modules\CompanyAssociation\Requests;

use App\Http\Requests\Request;

class CompanyAssociationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        $rules['request_type'] = 'required|in:Add,Remove';

        if ($this->request->get('request_type') === 'Remove') {
            $rules['remove_req_company_id'] = 'required';
        } elseif ($this->request->get('request_type') === 'Add') {
            if ($this->request->has('company_types')) {
                $rules['company_name_en'] = 'required';
                $rules['company_name_bn'] = 'required';
                $messages['company_name_en.required'] = 'The Company Name (English) field is required when company types is new.';
                $messages['company_name_bn.required'] = 'The Company Name (Bangla) field is required when company types is new.';
            } else {
                $rules['requested_company_id'] = 'required';
            }
            $rules['authorization_letter'] = 'required|mimes:pdf|max:3072';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if ($this->request->get('request_type') === 'Add') {
            if ($this->request->has('company_types')) {
                $messages['company_name_en.required'] = 'The Company Name (English) field is required when company types is new.';
                $messages['company_name_bn.required'] = 'The Company Name (Bangla) field is required when company types is new.';
            }
        }
        return $messages;
    }
}
