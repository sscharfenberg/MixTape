<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SpaController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        return response()->view('app');
    }

}
