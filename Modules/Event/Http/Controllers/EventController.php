<?php

namespace Modules\Event\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Http\Services\EventService;

class EventController extends Controller
{   
    protected $EventService; 

    public function __construct(EventService $service){
        $this->EventService = $service;
    }

    public function index(Request $request)
    {
        $response = $this->EventService->index($request);
        return ResponseFormatter::success(
            $response, 
        'Event Datas');
    }

    public function store(Request $request)
    {
        return $this->EventService->store($request);
    }

    public function show($id)
    {
        $response = $this->EventService->show($id);
        return ResponseFormatter::success(
            $response, 
        'Event Data');
    }

    public function update(Request $request, $id)
    {
        return $this->EventService->update($request,$id);
    }

    function updateImg(Request $request, $id)
    {
        return $this->EventService->updateImg($request,$id);
    }

    public function destroy($id)
    {
        return $this->EventService->destroy($id);
    }
}
