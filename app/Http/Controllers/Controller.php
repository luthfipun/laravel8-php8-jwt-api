<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function respondOkWithData($data){
        return $this->respond(true, 200, '', [$data]);
    }

    public function respondErrorWithMessage($msg){
        return $this->respond(false, 500, $msg);
    }

    public function respondOK(){
        return $this->respond(true, 200, 'OK');
    }

    private function respond($status = false, $code = 0, $message = '', $data = null){
        return response()->json([
            'success' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}
