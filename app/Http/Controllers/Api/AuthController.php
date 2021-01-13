<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Requests\Api\LoginRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!$token = auth()->attempt($data)) {
            return $this->response->setCode(401)
                ->setMessage('Giriş başarısız, bilgileri kontrol edip tekrar deneyin!')->respond();
        }

        return $this->createNewToken($token);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();

        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->respond();
    }

    protected function createNewToken($token)
    {
        $data = [
            'access_token' => $token,
        ];

        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->setData($data)->respond();
    }
}
