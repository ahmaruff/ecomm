<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function me(){
        $res = [
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'data' => [
                'user'=> auth()->user(),
            ],
        ];

        return response()->json($res, Response::HTTP_OK);
    }
}
