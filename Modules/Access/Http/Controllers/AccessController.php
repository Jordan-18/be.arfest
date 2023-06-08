<?php

namespace Modules\Access\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Access\Http\Services\AccessService;

class AccessController extends Controller
{
    protected $AccessService;

    public function __construct(AccessService $service)
    {
        $this->AccessService = $service;
    }
    public function index(Request $request)
    {
        $response = $this->AccessService->index($request);
        
        return ResponseFormatter::success(
            $response, 
        'Access Data');
    }

    public function store(Request $request)
    {
        return $this->AccessService->store($request);
    }

    public function show($id)
    {
        $response = $this->AccessService->show($id);
        return ResponseFormatter::success(
            $response,
            "Access By id"
        );
    }

    public function update(Request $request, $id)
    {
        return $this->AccessService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->AccessService->destroy($id);
    }
}
