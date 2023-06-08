<?php
namespace Modules\Point\http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Point\Entities\Point;
use Modules\Point\Entities\point_detail;
use Modules\Point\Entities\PointDetail;

class PointService{
    public function index($request)
    {
        $limit = $request->input('limit',5);
        $search = $request->input('search');
        $auth = $request->input('auth');

        $response = Point::with(['PointDetail'])
                    ->leftJoin('jenis_busurs','points.point_jenis_busur','=','jenis_busurs.jenis_busur_id')
                    ->leftJoin('users','points.point_user','=','users.user_id')
                    ->select(
                        'points.*',
                        'jenis_busurs.jenis_busur_name',
                        'users.username'
                    );

        if($search){
            $columns = ['point_jenis_busur','point_jarak','point_rambahan','point_jumlah_anak_panah','point_total'];
            
            foreach($columns as $key=>$value){
                if($key == 0){
                    $response->where($value,'like','%'.$search.'%');
                }
                $response->orWhere($value,'like','%'.$search.'%');
            }
        }

        if($auth){
            $response->where('points.point_user','=',$auth);
        }

        $response->orderBy('created_at','desc');

        return $response->paginate($limit);
    }
    public function store($request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'point_user' => ['required','string','max:32'],
                'point_jarak' => ['required','string','max:20'],
                'point_jenis_busur' => ['required','string','max:32'],
                'point_rambahan' => ['required','integer','max:20'],
                'point_jumlah_anak_panah' => ['required','integer','max:20'],
                'point_tanggal' => ['required','date'],
            ]);
            $created = [
                'point_user' => $request->point_user,
                'point_jarak' => $request->point_jarak,
                'point_jenis_busur' => $request->point_jenis_busur,
                'point_rambahan' => $request->point_rambahan,
                'point_jumlah_anak_panah' => $request->point_jumlah_anak_panah,
                'point_tanggal' => $request->point_tanggal,
            ];
            
            $total = 0;
            $point =  Point::create($created);
            foreach($request->point_detail as $key=>$value){
                $point_detail = [
                    'point_detail_induk' => $point->point_id,
                    'point_detail_points' => join(',',$value['pointResult']),
                    'point_detail_total' => (int)$value['sumTotal'],
                    'point_detail_img' => $value['imgData'],
                ];
                PointDetail::create($point_detail);

                $total += (int)$value['sumTotal'];
            }

            $presentase = ($total / (((int)$request->point_jumlah_anak_panah * 10) * (int)$request->point_rambahan)) * 100;

            Point::where('point_id', $point->point_id)->update([
                'point_total' => $total,
                'point_presentase' => $presentase,
            ]);
            
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
        $menu = Point::where('access_id', $id)->get();
        return $menu;
    }
    public function update($request,$id)
    {
        DB::beginTransaction();
        try {
            
            DB::commit();
            return ResponseFormatter::success(
                'Success'
            , 'Access Updated');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Something Wrong while Update new Point',
                'error' => $error->getMessage()
            ], 'Update Failed',500);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Point::where('point_id', $id)->delete();
            DB::commit();
            return ResponseFormatter::success(
            'Success'
            ,'Point Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => '',
                'error' => $error->getMessage()
            ], '',500);
        }
    }
}