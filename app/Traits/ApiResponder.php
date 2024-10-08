<?php

namespace App\Traits;

trait ApiResponder{
    private $data;
    private $code;
    protected function responseOnSuccess($code = 200, $data = null){
      return response()->json([
          'code' => $this->code = $code,
          'message' => "Success",
          'data' => $this->data = $data,
          'error' => "",
          'version' => "0.0.1a",
        ], $this->code = $code);
	  }
  	protected function responseOnError($code = 404, $data = null){
        return response()->json([
            'code' => $this->code = $code,
            'message' => "Failed",
            'data' => "",
            'error' => $this->data = $data,
            'version' => "0.0.1a",
        ], $this->code = $code);
  	}
}
