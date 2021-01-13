<?php

namespace App\Http\Requests\Api;

use App\Support\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|max:250',
            'password' => 'required|max:50',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'E-Posta',
            'password' => 'Şifre',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(
            422,
            'İstek datası doğrulamadan geçemedi!',
            (new ValidationException($validator))->errors()
        );

        throw new HttpResponseException($response->respond());
    }
}
