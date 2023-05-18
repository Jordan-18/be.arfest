<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\http\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $service)
    {
        $this->authService = $service;
    }
    public function register(Request $request)
    {
        return $this->authService->register($request);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function fetch(Request $request)
    {
        return $this->authService->fetch($request);
    }

    public function UpdateProfile(Request $request)
    {
        return $this->authService->UpdateProfile($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}
