<?php

namespace App\Traits;

trait ApiResponder{
    private $data;
    private $code;
    protected function responseOnSuccess($code = 200, $data = null, $message ="Success!"){
      return response()->json([
          'code' => $this->code = $code,
          'message' => $message,
          'data' => $this->data = $data,
          'error' => "",
          'version' => "0.1.1a",
        ], $this->code = $code);
	  }
  	protected function responseOnError($code = 404, $data = null, $message ="Failed!"){
        return response()->json([
            'code' => $this->code = $code,
            'message' => $message,
            'data' => "",
            'error' => $this->data = $data,
            'version' => "0.1.1a",
        ], $this->code = $code);
  	}
}
