<?php

namespace Modules\GAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gauth\http\Services\GAuthService;

class GAuthController extends Controller
{
    protected $gAuthService;
    
    public function __construct(GAuthService $gAuthService)
    {
        $this->gAuthService = $gAuthService;
    }

    
}
