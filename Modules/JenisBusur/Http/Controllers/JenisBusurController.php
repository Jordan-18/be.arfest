<?php

namespace Modules\JenisBusur\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\JenisBusur\Http\Services\JenisBusurService;

class JenisBusurController extends Controller
{
    protected $jenisBusurService;
    public function __construct(JenisBusurService $jenisBusurService)
    {
        $this->jenisBusurService = $jenisBusurService;
    }
    public function index(Request $request)
    {
        $response = $this->jenisBusurService->index($request);
        
        return ResponseFormatter::success(
            $response, 
        'Access Data');
    }

    public function store(Request $request)
    {
        return $this->jenisBusurService->store($request);
    }

    public function show($id)
    {
        $response = $this->jenisBusurService->show($id);
        return ResponseFormatter::success(
            $response,
            "Access By id"
        );
    }

    public function update(Request $request, $id)
    {
        return $this->jenisBusurService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->jenisBusurService->destroy($id);
    }
}
