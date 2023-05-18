<?php
namespace Modules\Menu\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Menu\Entities\Menu;

class MenuService{
    public function index($request)
    {

        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = Menu::query();

        if($search){
            $columns = ['menu_kode','menu_name','menu_order','menu_level','menu_endpoint'];
            
            foreach($columns as $key=>$value){
                if($key == 0){
                    $response->where($value,'like','%'.$search.'%');
                }
                $response->orWhere($value,'like','%'.$search.'%');
            }
        }

        return $response->paginate($limit);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'menu_kode'     => ['required','string','max:32'],
                'menu_name'     => ['required','string','max:32'],
                'menu_order'    => ['required','string','max:32'],
                'menu_level'    => ['required','string','max:32'],
                'menu_endpoint' => ['required','string','max:32'],
            ]);

            Menu::create([
                'menu_kode'     => $request->menu_kode, 
                'menu_name'     => $request->menu_name,
                'menu_order'    => $request->menu_order,
                'menu_hassub'   => $request->menu_hassub,
                'menu_parent'   => $request->menu_parent,
                'menu_level'    => $request->menu_level, 
                'menu_icon'     => $request->menu_icon, 
                'menu_endpoint' => $request->menu_endpoint, 
            ]);
            DB::commit();
            return ResponseFormatter::success([
                'Success'
            ], 'Menu Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan',
                'error' => $error
            ], 'Create Menu Failed',500);
        }
    }
    public function show($id)
    {
        $menu = Menu::where('menu_id', $id)->get();
        return $menu;
    }
    public function update($request,$id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'menu_kode'     => ['required','string','max:255'],
                'menu_name'     => ['required','string','max:255'],
                'menu_order'    => ['required','string','max:255'],
                'menu_level'    => ['required','string','max:255'],
                'menu_endpoint' => ['required','string','max:255'],
            ]);

            $updateData = [
                'menu_kode'     => $request->menu_kode, 
                'menu_name'     => $request->menu_name,
                'menu_order'    => $request->menu_order,
                'menu_hassub'   => $request->menu_hassub,
                'menu_parent'   => $request->menu_parent,
                'menu_level'    => $request->menu_level, 
                'menu_icon'     => $request->menu_icon, 
                'menu_endpoint' => $request->menu_endpoint, 
            ];

            Menu::where('menu_id', $id)->update($updateData);
            
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            ,'Menu Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error
            ], 'User Register Failed',500);
        }
    }
    public function destroy($id){
        DB::beginTransaction();
        try {
            Menu::where('menu_id', $id)->delete();

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

    public function menus()
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