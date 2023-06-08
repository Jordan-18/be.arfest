<?php
namespace Modules\Menu\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Access\Entities\Access;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuAccess;
use PDO;

class MenuAccessService{

    protected $menuService;
    protected $nestedMenu;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }
    public function index()
    {
        $response = MenuAccess::get();
        return $response;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'menu_access_menu' => ['required','string'],
                'menu_access_access' => ['required','string'],
            ]);

            MenuAccess::create([
                'menu_access_menu'   => $request->menu_access_menu,
                'menu_access_access' => $request->menu_access_access,
            ]);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Menu Access Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while store new Menu Access',
                'error' => $error->getMessage()
            ], 'Store Failed',500);
        }
    }

    public function show($id)
    {
        $response = [];

        $menus = Menu::orderBy('menu_order')->get();
        $menuAccess = MenuAccess::where('menu_access_access', $id)->get();
        $menuAccess = $menuAccess->pluck('menu_access_menu');

        $result01 = $menus->filter(function ($menu) use ($menuAccess){
            return $menuAccess->contains($menu['menu_id']);
        });

        foreach ($result01 as $k2=>$v2) {
            $v2['menus'] = $result01->where('menu_parent', $v2['menu_id'])->values();

            if(!$v2['menu_parent']){
                $response[] = $v2;
            }
        }

        return $response;
    }

    public function update($request,$id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'data' => ['required']
            ]);

            MenuAccess::where('menu_access_access', $id)->delete();

            foreach($request->data as $key=>$value){
                $checked = $value['state']['checked'] ?? false;
                $indeterminate = $value['state']['indeterminate'] ?? false;

                if($checked || $indeterminate){
                    MenuAccess::firstOrCreate([
                        'menu_access_access' => $id,
                        'menu_access_menu'   => $key
                    ]);
                }
            }
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Role Menu Access Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Kesalahan dalam program',
                'error' => $error->getMessage()
            ], 'Kesalahan dalam program',500);
        }
    }
    
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            MenuAccess::where('menu_access_id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success(
                'Success'
            ,'Menu Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Menu Access Failed Failed',
                'error' => $error->getMessage()
            ], 'Menu Access Failed Failed',500);
        }
    }

    public function roleAccess($id)
    {
        $response = [];

        $menus = $this->menuService->menus();
        $accessMenu = $this->show($id);

        $menus = $this->nestedMenu($menus);

        $this->nestedMenu = [];
        $accessMenu = $this->nestedMenu($accessMenu);

        foreach($menus as $key=>$value){
            $response[$key] = $value;
            $response[$key]['state']['opened'] = true;
            if(isset($accessMenu[$key])){
                $response[$key]['state']['checked'] = true;
            }
        }
        
        return $response;
    }
    
    public function nestedMenu($data = [])
    {        
        foreach($data as $key=>$value){
            $this->nestedMenu[$value['menu_id']] = array(
                'text' => $value['menu_name'],
                'children' => $value['menus']->pluck('menu_id')
            );

            $this->nestedMenu($value['menus']);
        }

        return $this->nestedMenu;
    }

    public function updateRoleAccess($request,$id)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            // $request->validate([
            //     'data' => ['required']
            // ]);

            var_dump($request);
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Kesalahan dalam program',
                'error' => $error->getMessage()
            ], 'Kesalahan dalam program',500);
        }
    }
}