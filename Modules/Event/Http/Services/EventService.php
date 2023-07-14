<?php
namespace Modules\Event\Http\Services;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Event\Entities\Event;

class EventService{
    protected $endpointS3;

    public function __construct(){
        $this->endpointS3 = env('AWS_ENDPOINT').'/'.env('AWS_BUCKET');
    }

    function index($request) {
        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = Event::with([
            'eventCreatedBy' => function($query){
                $query->select('user_id','email','username');
            },
            'eventUpdatedBy'=> function($query){
                $query->select('user_id','email','username');
            }
        ]);

        if($search){
            $columns = ['event_kode','event_name'];
            
            foreach($columns as $key=>$value){
                if($key == 0){
                    $response->where($value,'like','%'.$search.'%');
                }
                $response->orWhere($value,'like','%'.$search.'%');
            }
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
                'event_description'=> ['required','string'],
                'event_status'     => ['required','string'],
                'event_created_by' => ['required','string','max:32'],
                'event_date_actual'=> ['required'],
            ]);

            $result = [
                'event_name'       => $request->event_name,
                'event_description'=> $request->event_description,
                'event_status'     => $request->event_status,
                'event_created_by' => $request->event_created_by,
                'event_date_actual'=> $request->event_date_actual,
            ];

            if($request->hasFile('event_img')){
                $filePath = Storage::disk('s3')->put('events', $request->file('event_img'));
                            Storage::disk('s3')->setVisibility($filePath, 'public');

                $urlImg = $this->endpointS3.'/'.$filePath;   

                $result['event_img'] = $urlImg;
            }


            Event::create($result);
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

            
            $result = [
                'event_name'       => $request->event_name,
                'event_description'=> $request->event_description,
                'event_status'     => $request->event_status,
                'event_updated_by' => $request->event_updated_by,
                'event_date_actual' => $request->event_date_actual,
            ];

            if($request->hasFile('event_img')){
                $data = Event::where('event_id',$id)->first();
                $imgUrl = $data['event_img']; $endpoint = $this->endpointS3.'/';

                $filePathOld = str_replace($endpoint, "", $imgUrl);
                Storage::disk('s3')->delete($filePathOld);

                $filePath = Storage::disk('s3')->put('events', $request->file('event_img'));
                            Storage::disk('s3')->setVisibility($filePath, 'public');

                $urlImg = $this->endpointS3.'/'.$filePath; 
                $result['event_img'] = $urlImg;
            }

            Event::where('event_id',$id)->update($result);
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
    function updateImg($request, $id){
        DB::beginTransaction();
        try {
            $request->validate([
                'event_img' => ['required'],
            ]);

            if($request->hasFile('event_img')){
                $data = Event::where('event_id',$id)->first();
                $imgUrl = $data['event_img']; $endpoint = $this->endpointS3.'/';

                $filePathOld = str_replace($endpoint, "", $imgUrl);
                Storage::disk('s3')->delete($filePathOld);

                $filePath = Storage::disk('s3')->put('events', $request->file('event_img'));
                            Storage::disk('s3')->setVisibility($filePath, 'public');

                $urlImg = $this->endpointS3.'/'.$filePath; 
                $result['event_img'] = $urlImg;

                Event::where('event_id',$id)->update($result);
            }

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
            $data = Event::where('event_id',$id)->first();
            $filePathOld = str_replace($this->endpointS3.'/', "", $data['event_img']);
            Storage::disk('s3')->delete($filePathOld);
            
            Event::where('event_id', $id)->delete();

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