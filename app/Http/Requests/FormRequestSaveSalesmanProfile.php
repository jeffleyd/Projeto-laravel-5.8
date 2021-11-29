<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestSaveSalesmanProfile extends FormRequest
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

        $profile_type = $this->request->get('profile_type');

        if($profile_type==1){
            return [
                'company_name' => 'required|max:120',
                'email' => 'required|email:rfc,dns',
                'phone_1' => 'required|max:60',
                'phone_2' => 'required|max:60',
            ];
        }else if($profile_type==2){
            return [
                'address' => 'required|max:30',
                'zipcode' => 'required|max:30',
                'state' => 'required',
                'city' => 'required|max:60',
            ];
        }else if($profile_type==3){
            return [
                'current_password' => 'required_with:new_password',
                'new_password' => 'required_with:current_password',
            ];
        }else{
            return [
                'first_name' => 'required|max:30',
                'last_name' => 'required|max:30',
                'email' => 'required|email:rfc,dns',
                'phone_1' => 'required|max:60',
                'phone_2' => 'required|max:60',
                'address' => 'required|max:30',
                'zipcode' => 'required|max:30',
                'state' => 'required',
                'city' => 'required|max:60',
            ];
        }

        
    }

    //caso tenha uma validação adicional
    // public function withValidator($validator){

    //     $validator->after(function($validator)
    //     {
            
    //         // $validator->errors()->add('item_field', 'item_field required');

    //     });
    // }


    public function attributes()
    {
        return [
            'first_name'=>'Primeiro Nome',
            'last_name'=>'Último Nome',
            'phone_1'=>'Telefone 1',
            'phone_2'=>'Telefone 2'
        ];
    }
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Campo obrigatório.',
            'current_password.required_with' => 'Você deve informar a senha atual.',
            'new_password.required_with' => 'Você deve informar a nova senha.',
        ];
    }
}
