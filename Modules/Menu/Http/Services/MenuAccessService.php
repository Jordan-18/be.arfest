<?php
namespace Modules\Menu\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Access\Entities\Access;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuAccess;

class MenuAccessService{

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
                'error' => $error
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
            $v2['child'] = $result01->where('menu_parent', $v2['menu_id'])->values();

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
            DB::commit();
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error
            ], 'User Register Failed',500);
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
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error
            ], 'User Register Failed',500);
        }
    }

    public function getAccess($id)
    {
        $response = Menu::with(['menus' => function($q1){
            $q1->with(['menus' => function($q2){
                $q2->with(['menus'])
                    ->where(['menu_level' => '3','menu_status' => '1'])
                    ->select('menu_id','menu_name','menu_parent','menu_icon','menu_endpoint')
                    ->orderBy('menu_order', 'ASC');
            }])
                ->where(['menu_level' => '2','menu_status' => '1'])
                ->select('menu_id','menu_name','menu_parent','menu_icon','menu_endpoint')
                ->orderBy('menu_order', 'ASC');
        }])
            ->where(['menu_level' => '1','menu_status' => '1'])
            ->select('menu_id','menu_name','menu_parent','menu_icon','menu_endpoint')
            ->orderBy('menu_order', 'ASC')->get();
        
        return $response;
    }
}