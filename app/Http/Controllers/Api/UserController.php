<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Requests\Api\LoginRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')
            ->except('login', 'register');

        parent::__construct();
    }

    public function store(Request $request)
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

    public function profile()
    {
        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->setData(auth()->user())->respond();
    }
}
