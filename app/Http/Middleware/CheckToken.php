<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->header('token')) {
            return \Response::json([
                'status' => 0,
                'error' => 'No token key'], 401);
        }
        $user = $this->repository->findWhere(['remember_token' => $request->header('token')])->first();
        if (!$user) {
            return \Response::json([
                'status' => 0,
                'error' => 'Unauthorized'], 401);
        } else {
            Auth::login($user);
        }

        return $next($request);
    }
}
