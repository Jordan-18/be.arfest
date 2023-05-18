<?php

namespace Modules\Menu\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Access\http\Services\AccessService;
use Modules\Menu\http\Services\MenuAccessService;
use Modules\Menu\http\Services\MenuService;

class MenuAccessController extends Controller
{
    protected $menuAccessService;
    protected $menuService;
    protected $accessService;

    public function __construct(MenuAccessService $service01, MenuService $service02, AccessService $service03)
    {
        $this->menuAccessService = $service01;
        $this->menuService       = $service02;
        $this->accessService     = $service03;
    }
    public function index()
    {   
        $response = $this->menuAccessService->index();
        return ResponseFormatter::success(
            $response, 
        'Menu Access Data');
    }

    public function store(Request $request)
    {
        return $this->menuAccessService->store($request);
    }

    public function show($id)
    {
        $response = $this->menuAccessService->show($id);
        return ResponseFormatter::success(
            $response, 
        'Menu Access Data');
    }

    public function update(Request $request, $id)
    {
        return $this->menuAccessService->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->menuAccessService->destroy($id);
    }

    public function getAccess($id)
    {
        $response = $this->menuAccessService->getAccess($id);

        return ResponseFormatter::success(
            $response,
            'Show Accesess'
        );
    }
}