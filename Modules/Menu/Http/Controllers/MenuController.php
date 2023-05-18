<?php

namespace Modules\Menu\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menu\http\Services\MenuService;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $service)
    {
        $this->menuService = $service;
    }
    public function index(Request $request)
    {
        $response = $this->menuService->index($request);
        return ResponseFormatter::success(
            $response,
        'Menu Data');
    }

    public function store(Request $request)
    {
        return $this->menuService->store($request);
    }

    public function show($id)
    {
        $response = $this->menuService->show($id);
        return ResponseFormatter::success(
            $response,
            "Menu By id"
        );
    }

    public function update(Request $request, $id)
    {
        return $this->menuService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->menuService->destroy($id);
    }

    public function menus()
    {
        $response = $this->menuService->menus();
        return ResponseFormatter::success(
            $response, 
        'Menu Data');
    }
}
