<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('email', 'password'), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if ($valid->fails()) {
            return \Response::json([
                'status' => 0,
                'error' => $valid->errors()->all()], 400);
        }

        $data = request()->only('email', 'password');
        $result = $request->session()->all();
        $token = $result['_token'];

        try {
            $user = $this->repository->create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'remember_token' => $token
            ]);
        } catch (\Exception $exception) {
            return \Response::json([
                'status' => 0,
                'error' => $exception->getMessage()], $exception->getCode());
        }

        return \Response::json([
            'status' => 1,
            'data' => $user->remember_token], 201);
    }
}
