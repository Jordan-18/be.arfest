<?php
namespace Modules\Menu\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Menu\Entities\Menu;

class MenuService{

    protected $menusResult;
    protected $countMenu;
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
            ]);

            $result = [
                'menu_kode'     => $request->menu_kode, 
                'menu_name'     => $request->menu_name,
                'menu_parent'   => $request->menu_parent,
                'menu_icon'     => $request->menu_icon, 
                'menu_order'    => 0, 
            ];

            $result['menu_endpoint'] = $request->menu_endpoint;
            $result['menu_level'] = '1';
            
            if(isset($request->menu_parent)){
                $getLevel = Menu::where('menu_id', $request->menu_parent)
                            ->select('menu_level')
                            ->get();

                $result['menu_level'] = ((int)$getLevel[0]['menu_level']) + 1;
            }

            // if($result['menu_level'] = '1'){
            //     $result['menu_endpoint'] = '#';
            // }

            Menu::create($result);

            DB::commit();
            return ResponseFormatter::success([
                'Success'
            ], 'Menu Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan',
                'error' => $error->getMessage()
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
                'menu_endpoint' => ['required','string','max:255'],
            ]);

            $result = [
                'menu_kode'     => $request->menu_kode, 
                'menu_name'     => $request->menu_name,
                'menu_parent'   => $request->menu_parent, 
                'menu_icon'     => $request->menu_icon, 
                'menu_endpoint' => $request->menu_endpoint, 
            ];

            $result['menu_level'] = '1';

            if(isset($request->menu_parent)){
                $getLevel = Menu::where('menu_id', $request->menu_parent)
                            ->select('menu_level')
                            ->get();

                $result['menu_level'] = ((int)$getLevel[0]['menu_level']) + 1;
            }

            Menu::where('menu_id', $id)->update($result);
            
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            ,'Menu Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan',
                'error' => $error->getMessage()
            ], 'Menu Failed Update',500);
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
                'error' => $error->getMessage()
            ], 'User Register Failed',500);
        }
    }

    public function menus()
    {
        $this->countMenu = 1;
        $this->menusResult = Menu::with(['menus' => function($q1){
            $this->menuRecursive($q1);
        }])
        ->where(['menu_level' => $this->countMenu,'menu_status' => '1'])
        ->select('menu_id','menu_name','menu_parent','menu_icon','menu_endpoint')
        ->orderBy('menu_order', 'ASC')->get();
        
        return $this->menusResult;
    }

    public function menuRecursive($query)
    {
        $this->countMenu++;
        
        $query->with(['menus' => function($q2){
            $this->menuRecursive($q2);
        }])
        ->where(['menu_level' => $this->countMenu,'menu_status' => '1'])
        ->select('menu_id','menu_name','menu_parent','menu_icon','menu_endpoint')
        ->orderBy('menu_order', 'ASC');
    }
}