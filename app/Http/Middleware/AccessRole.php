<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\Menu\Http\Services\MenuAccessService;

class AccessRole
{
    protected $menuAccessService;
    function __construct(MenuAccessService $menuAccessService)
    {
        $this->menuAccessService = $menuAccessService;
    }
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()){
            $auth = $this->menuAccessService->show(Auth::user()->useraccess);

            foreach($auth as $key=>$value){
                if($value['menu_endpoint'] == $request){
                    return $next($request);
                }else{
                    $this->accessNested($value, $request, $next);
                }
            }

            return Redirect::to(env('FRONTEND_URL'));
        }
        return Redirect::to(env('FRONTEND_URL'));
    }

    function accessNested($data ,$request, $next) {
        foreach($data as $key=>$value){
            if($value['menu_endpoint'] == $request){
                return $next($request);
            }else{
                $this->accessNested($value, $request, $next);
            }
        }
    }
}
