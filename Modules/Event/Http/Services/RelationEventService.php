<?php
namespace Modules\Event\Http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\RelationEvent;

class RelationEventService{
    function index($request) {
        $limit = $request->input('limit',10);
        $search = $request->input('search');
        $auth = $request->input('auth');

        function FunctionName(){
            
        }

        $response = RelationEvent::with([
            'relationevent' => function($query){
                $query->select('event_id','event_name','event_date_actual');
            },
            'relationuser' => function($query){
                $query->select('user_id','email','username');
            },
            'relationpoint' => function($query){
                $query->select('point_id','point_jumlah_anak_panah','point_total');
            },
        ]);

        if($search){
            $columns = ['relationevent->event_name','relationpoint->point_total'];
            
            foreach($columns as $key=>$value){
                if($key == 0){
                    $response->where($value,'like','%'.$search.'%');
                }
                $response->orWhere($value,'like','%'.$search.'%');
            }
        }

        if($auth){
            $response->where('relation_event_user','=',$auth);
        }

        return $response->paginate($limit);
    }
    function show($id) {
        $menu = Event::where('event_id', $id)->get();
        return $menu;
    }
    function store($request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'event_name'       => ['required','string','max:128'],
                'event_img'        => ['required'],
            ]);

            Event::create($request);
            DB::commit();

            return ResponseFormatter::success(
                'Success'
            ,'Event Created');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan',
                'error' => $error->getMessage()
            ], 'Event Create Failed',500);
        }
    }
    function update($request, $id) {
        DB::beginTransaction();
        try {
            
            $request->validate([
                'event_name'       => ['required','string','max:128'],
                'event_description'=> ['required','string'],
                'event_updated_by' => ['required','string','max:32'],
                'event_date_actual'=> ['required','string'],
            ]);

            
            DB::commit();

            return ResponseFormatter::success(
                'Success'
            ,'Event Updated');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan',
                'error' => $error->getMessage()
            ], 'Event Update Failed',500);
        }
    }
    function destroy($id) {
        DB::beginTransaction();
        try {

            DB::commit();
            return ResponseFormatter::success(
                'Success'
            ,'Event Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error->getMessage()
            ], 'Event Delete Failed',500);
        }
    }
}