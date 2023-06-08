<?php
namespace Modules\JenisBusur\Http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\JenisBusur\Entities\JenisBusur;

class JenisBusurService{
    public function index($request)
    {
        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = JenisBusur::query();

        if($search){
            $columns = ['jenis_busur_name'];
            
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
                'jenis_busur_name' => ['required','string','max:32'],
                'jenis_busur_kategori' => ['required','string','max:32'],
            ]);

            JenisBusur::create([
                'jenis_busur_name' => $request->jenis_busur_name,
                'jenis_busur_kategori' => $request->jenis_busur_kategori,
            ]);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Jenis Busur Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while store new Jenis Busur',
                'error' => $error->getMessage()
            ], 'Store Failed',500);
        }
    }
    public function show($id)
    {
        $menu = JenisBusur::where('jenis_busur_id', $id)->get();
        return $menu;
    }
    public function update($request,$id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'jenis_busur_name' => ['required','string','max:32'],
                'jenis_busur_kategori' => ['required','string','max:32'],
            ]);

            $updateData = [
                'jenis_busur_name' => $request->jenis_busur_name,
                'jenis_busur_kategori' => $request->jenis_busur_kategori,
            ];
            JenisBusur::where('jenis_busur_id', $id)->update($updateData);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Jenis Busur Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while Update new Jenis Busur',
                'error' => $error->getMessage()
            ], 'Update Failed',500);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            JenisBusur::where('jenis_busur_id', $id)->delete();
            DB::commit();
            return ResponseFormatter::success(
            'Success'
            ,'Jenis Busur Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while Delete Jenis Busur',
                'error' => $error->getMessage()
            ], 'Delete Failed',500);
        }
    }
}