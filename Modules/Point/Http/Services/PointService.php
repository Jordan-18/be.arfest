<?php
namespace Modules\Point\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Point\Entities\Point;

class PointService{
    public function index($request)
    {
        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = Point::query();

        if($search){
            $columns = ['point_jenis_busur','point_jarak','point_rambahan','point_jumlah_anak_panah','point_total'];
            
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
                'point_user' => ['required','string','max:32'],
                'point_jarak' => ['required','string','max:20'],
                'point_jenis_busur' => ['required','string','max:20'],
                'point_rambahan' => ['required','string','max:20'],
                'point_jumlah_anak_panah' => ['required','string','max:20'],
                'point_total' => ['required','string','max:32'],
            ]);

            $created = [
                'point_user' => $request->point_user,
                'point_jarak' => $request->point_jarak,
                'point_jenis_busur' => $request->point_jenis_busur,
                'point_rambahan' => $request->point_rambahan,
                'point_jumlah_anak_panah' => $request->point_jumlah_anak_panah,
                'point_total' => $request->point_total,
            ];
             
            Point::create($created);
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Point Created');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while store new Point',
                'error' => $error->getMessage()
            ], 'Failed Store Point',500);
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