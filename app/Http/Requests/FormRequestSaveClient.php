<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestSaveClient extends FormRequest
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

        return [
            'company_name' => 'required',
            'type_people' => 'required',
            'identity' => 'required',
            'state_registration' => 'required',
            'municipal_registration' => 'required',
            'is_matriz' => 'required',      
            'code_description_ativity' => 'required',
            'suframa_registration' => 'required',
            'especial_regime_icms_per_st' => 'required',
            'tax_regime' => 'required',
            'social_capital' => 'required',
            'nire_number' => 'required',
            'type_client' => 'required',
            'product_sale' => 'required',
            'address' => 'required|max:200',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required|max:30',
            'billing_location_identity' => 'required',
            'billing_location_state_registration' => 'required',
            'billing_location_address' => 'required',
            'billing_location_city_state' => 'required',
            'delivery_location_identity' => 'required',
            'delivery_location_state_registration' => 'required',
            'delivery_location_address' => 'required',
            'delivery_location_city_state' => 'required',
            'quantity_filial_cds' => 'required',
            'units_air_sold_last_years' => 'required',
            'works_import' => 'required',
            'cp_name' => 'required',
            'cp_office' => 'required',
            'cp_email' => 'required',
            'cp_phone' => 'required',
            'cf_name' => 'required',
            'cf_office' => 'required',
            'cf_email' => 'required',
            'cf_phone' => 'required',
            'cl_name' => 'required',
            'cl_office' => 'required',
            'cl_email' => 'required',
            'cl_phone' => 'required'
        ];
    }

    /*public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {

                if($request->has('tab')){
                    $request->session()->flash('tab', $request->tab);
                }
                //$validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }*/

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Campo obrigatório.'
        ];
    }
}
