<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TransactionRequest extends FormRequest
{
    use ApiResponser;
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
            'accountId'   => 'required|exists:finance_accounts,id,user_id,'.auth()->user()->id,
            'financeName' => 'required',
            'amount'      => 'required|numeric',
            'description' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator) {
       
        $response = $this->setResponse(400, false, $validator->getMessageBag()->first());
        
        throw (new ValidationException($validator, $response))->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
    }
}
