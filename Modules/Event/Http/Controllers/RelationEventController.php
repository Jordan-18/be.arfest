<?php

namespace Modules\Event\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Http\Services\RelationEventService;

class RelationEventController extends Controller
{
    protected $EventRelationService;

    public function __construct(RelationEventService $service){
        $this->EventRelationService = $service;
    }
    public function index(Request $request)
    {
        $response = $this->EventRelationService->index($request);
        return ResponseFormatter::success(
            $response, 
        'Event Datas');
    }

    public function store(Request $request)
    {
        return $this->EventRelationService->store($request);
    }

    public function show($id)
    {
        $response = $this->EventRelationService->show($id);
        return ResponseFormatter::success(
            $response, 
        'Event Data');
    }

    public function update(Request $request, $id)
    {
        return $this->EventRelationService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->EventRelationService->destroy($id);
    }
}
