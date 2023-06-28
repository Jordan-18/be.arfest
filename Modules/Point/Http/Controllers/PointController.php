<?php

namespace Modules\Point\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Point\Http\Services\PointService;

class PointController extends Controller
{
    protected $pointService;
    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }
    
    public function index(Request $request)
    {
        $response = $this->pointService->index($request);
        
        return ResponseFormatter::success(
            $response, 
        'Access Data');
    }

    public function store(Request $request)
    {
        return $this->pointService->store($request);
    }

    public function destroy($id)
    {
        return $this->pointService->destroy($id);
    }

    public function printPoint($id)
    {
        $response =$this->pointService->printPoint($id);
        
        return ResponseFormatter::success(
            $response, 
        'Point Print Data');
    }
}
