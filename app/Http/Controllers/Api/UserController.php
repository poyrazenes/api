<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Requests\Api\UserRequest;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return $this->response->setCode(201)
            ->setMessage('User successfully registered')->respond();
    }

    public function profile()
    {
        return $this->response->setCode(200)
            ->setMessage('İşlem başarılı.')->setData(auth()->user())->respond();
    }
}
