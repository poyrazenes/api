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
        $this->middleware('auth:api')
            ->except('login', 'register');

        parent::__construct();
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!$token = auth()->attempt($data)) {
            return $this->response->setCode(401)
                ->setMessage('Yetkilendirme yapılamadı, lütfen tekrar deneyin!')->respond();
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        $data = $validator->validated();

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function userProfile()
    {
        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->setData(auth()->user())->respond();
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
