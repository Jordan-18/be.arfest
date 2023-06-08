<?php
namespace Modules\User\Http\Services;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function index($request)
    {
        $limit = $request->input('limit',10);
        $search = $request->input('search');

        $response = User::query();

        if($search){
            $columns = ['name','email'];
            
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
                'name'      => ['required','string','max:128'],
                'username'  => ['required','string','max:128','unique:users'],
                'email'     => ['required','string','email','max:64','unique:users'],
                'password'  => ['required','string'],
            ]);

            User::create([
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
            ]); 
            
            DB::commit();
            return ResponseFormatter::success([
                'New user has been created'              
            ],  'User Created');
        } 
        catch(Exception $error)
        {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'User Failed Created',
                'error' => $error->getMessage()
            ], 'User Failed Created',500);
        }
    }

    public function show($id)
    {
        $user = User::where('user_id', $id)->get();
        return $user;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name'      => ['required','string','max:128'],
                'username'  => ['required','string','max:128'],
                'email'     => ['required','string','email','max:64'],
            ]);

            $update = [
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
            ];

            if(isset($request->password) && $request->password != ""){
                $update['password'] = Hash::make($request->password);
            }

            if(isset($request->useraccess) && $request->useraccess != ""){
                $update['useraccess'] = $request->useraccess;
            }

            User::where('user_id',$id)->update($update); 
            DB::commit();

            return ResponseFormatter::success([
                'New user has been Updated'              
            ],  'User Updated');
        } 
        catch(Exception $error)
        {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'User Failed Updated',
                'error' => $error->getMessage()
            ], 'User Failed Updated',500);
        }
    }

    public function destroy($id){
        DB::beginTransaction();
        try {
            User::where('user_id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success(
                'Success'
            ,'User Deleted');
        } 
        catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan pada program',
                'error' => $error->getMessage()
            ], 'User Failed Deleted',500);
        }
    }
}