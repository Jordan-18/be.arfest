<?php
namespace Modules\Access\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Access\Entities\Access;

class AccessService{

    public function index($request)
    {
        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = Access::query();

        if($search){
            $columns = ['access_kode','access_name'];
            
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
                'access_kode' => ['required','string','max:32'],
                'access_name' => ['required','string','max:32'],
            ]);

            Access::create([
                'access_kode' => $request->access_kode,
                'access_name' => $request->access_name,
            ]);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Access Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while store new Access',
                'error' => $error->getMessage()
            ], 'Store Failed',500);
        }
    }
    public function show($id)
    {
        $menu = Access::where('access_id', $id)->get();
        return $menu;
    }
    public function update($request,$id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'access_kode' => ['required','string','max:32'],
                'access_name' => ['required','string','max:32'],
            ]);

            $updateData = [
                'access_kode' => $request->access_kode,
                'access_name' => $request->access_name,
            ];
            Access::where('access_id', $id)->update($updateData);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Access Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while Update new Access',
                'error' => $error->getMessage()
            ], 'Update Failed',500);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Access::where('access_id', $id)->delete();
            DB::commit();
            return ResponseFormatter::success(
            'Success'
            ,'Access Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error->getMessage()
            ], 'User Register Failed',500);
        }
    }
}