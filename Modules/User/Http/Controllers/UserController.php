<?php

namespace Modules\User\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $response = $this->userService->index($request);
        return ResponseFormatter::success(
            $response,
        'User Data');
    }

    public function store(Request $request)
    {
        return $this->userService->store($request);
    }

    public function show($id)
    {
        $response = $this->userService->show($id);
        return ResponseFormatter::success(
            $response,
            "User By id"
        );
    }

    public function update(Request $request, $id)
    {
        return $this->userService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->userService->destroy($id);
    }
}
