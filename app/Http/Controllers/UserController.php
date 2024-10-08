<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function detail($id)
    {
        if (!$id){
            return $this->responseOnError(404,"Data Not Found!");
        } else {
            try{
                $user = User::findOrFail($id);
                return $this->responseOnSuccess(200,$user);
            } catch (\Exception $e){
                return $this->responseOnError(404,"Error! ->".$e);
            }
        }
    }
    public function profile(Request $request)
    {
        try{
            $user = auth('sanctum')->user();
            return $this->responseOnSuccess(200,$user);
        } catch (\Exception $e){
            return $this->responseOnError(404,"Error! ->".$e);
        }
    }
}
