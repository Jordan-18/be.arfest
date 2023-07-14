<?php
namespace Modules\Menu\Http\Services;

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
        })->map(function ($menu) use ($id) {
            $menuAccessItem =  MenuAccess::where([
                'menu_access_menu' => $menu['menu_id'],
                'menu_access_access' => $id
            ])->first();

            if ($menuAccessItem !== null && $menu['menu_endpoint'] != '#'){
                $menu['create'] = $menuAccessItem['menu_access_create'];
                $menu['read']   = $menuAccessItem['menu_access_read'];
                $menu['update'] = $menuAccessItem['menu_access_update'];
                $menu['delete'] = $menuAccessItem['menu_access_delete'];
            }
            return $menu;
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
                $checkAccess = false;

                $check = explode("#",$key);

                if(!isset($check[1])){
                    $checkAccess = !isset($check[1]);
                }

                
                if(($checked || $indeterminate) && $checkAccess){
                    $created = [
                        'menu_access_access' => $id,
                        'menu_access_menu'   => $key,
                    ];

                    if(isset($request->data[$key.'#CREATE'])){
                        $CREATE = $key.'#CREATE'; $READ = $key.'#READ'; $UPDATE = $key.'#UPDATE'; $DELETE = $key.'#DELETE';
    
                        $createValue = isset($request->data[$CREATE]['state']['checked']) && $request->data[$CREATE]['state']['checked'] ? 1 : 0;
                        $readValue = isset($request->data[$READ]['state']['checked']) && $request->data[$READ]['state']['checked'] ? 1 : 0;
                        $updateValue = isset($request->data[$UPDATE]['state']['checked']) && $request->data[$UPDATE]['state']['checked'] ? 1 : 0;
                        $deleteValue = isset($request->data[$DELETE]['state']['checked']) && $request->data[$DELETE]['state']['checked'] ? 1 : 0;

                        $created['menu_access_create']  = $createValue;
                        $created['menu_access_read']    = $readValue;
                        $created['menu_access_update']  = $updateValue;
                        $created['menu_access_delete']  = $deleteValue;
                    }
                    
                    MenuAccess::firstOrCreate($created);
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
        
        $menus = $this->nestedMenu($menus, 'menu');
        
        $this->nestedMenu = [];
        $accessMenu = $this->nestedMenu($accessMenu);

        foreach($menus as $key=>$value){
            $response[$key] = $value;

            $response[$key]['state']['opened'] = false;
            if(isset($accessMenu[$key])){
                $checked = 'checked';
                $checkChildren01 = isset($accessMenu[$key]['children']) && (count($accessMenu[$key]['children']) > 0);
                $checkChildren02 = false;

                if($checkChildren01){
                    foreach($accessMenu[$key]['children'] as $key2=>$value2){
                        if(isset($accessMenu[$value2])){
                            $checkChildren02 = true;
                            $response[$key]['state']['opened'] = true;
                        }
                        
                        $check = explode("#",$accessMenu[$key]['children'][0]);
                        if(isset($check[1])){
                            $response[$key]['state']['opened'] = false;
                        }
                    }
                }

                if($checkChildren02){
                    $checked = 'indeterminate';
                }
                
                $response[$key]['state'][$checked] = true;
            }
        }
        
        return $response;
    }
    
    public function nestedMenu($data = [], $role = '')
    {        
        foreach($data as $key=>$value){
            $children = [];
            if($value['menu_endpoint'] != '#'){
                $children = [
                    $value['menu_id'].'#CREATE', 
                    $value['menu_id'].'#READ', 
                    $value['menu_id'].'#UPDATE', 
                    $value['menu_id'].'#DELETE'
                ];
            }else{
                $children = $value['menus']->pluck('menu_id');
            }
            $this->nestedMenu[$value['menu_id']] = array(
                'text' => $value['menu_name'],
                'children' => $children,
            );

            if($value['menu_endpoint'] != '#'){
                if($role == 'menu'){
                    $this->nestedMenu[$value['menu_id'].'#CREATE'] = array('text' => 'CREATE');
                    $this->nestedMenu[$value['menu_id'].'#READ'] = array('text' => 'READ');
                    $this->nestedMenu[$value['menu_id'].'#UPDATE'] = array('text' => 'UPDATE');
                    $this->nestedMenu[$value['menu_id'].'#DELETE'] = array('text' => 'DELETE');
                }

                if(isset($value['create']) && $value['create'] == 1){
                    $this->nestedMenu[$value['menu_id'].'#CREATE'] = array('text' => 'CREATE');
                }
                if(isset($value['read']) && $value['read'] == 1){
                    $this->nestedMenu[$value['menu_id'].'#READ'] = array('text' => 'READ');
                }
                if(isset($value['update']) && $value['update'] == 1){
                    $this->nestedMenu[$value['menu_id'].'#UPDATE'] = array('text' => 'UPDATE');
                }
                if(isset($value['delete']) && $value['delete'] == 1){
                    $this->nestedMenu[$value['menu_id'].'#DELETE'] = array('text' => 'DELETE');
                }
            }

            $this->nestedMenu($value['menus'], $role);
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

    public function rolePureAccess($id)
    {
        $response = [];
        $menus = Menu::orderBy('menu_order')->get();
        $menuAccess = MenuAccess::where('menu_access_access', $id)->get();
        $menuAccess = $menuAccess->pluck('menu_access_menu');

        
        $result01 = $menus->filter(function ($menu) use ($menuAccess){
            return $menuAccess->contains($menu['menu_id']);
        })->map(function ($menu) use ($id) {
            $menuAccessItem =  MenuAccess::where([
                'menu_access_menu' => $menu['menu_id'],
                'menu_access_access' => $id
            ])->first();

            if ($menuAccessItem !== null && $menu['menu_endpoint'] != '#'){
                $menu['create'] = $menuAccessItem['menu_access_create'];
                $menu['read']   = $menuAccessItem['menu_access_read'];
                $menu['update'] = $menuAccessItem['menu_access_update'];
                $menu['delete'] = $menuAccessItem['menu_access_delete'];
            }
            return $menu;
        });

        foreach ($result01 as $k2=>$v2) {
            $response[] = $v2;
        }

        return $response;
    }
}