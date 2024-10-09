<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        if($validator->fails()){
            return $this->responseOnError(403,$validator->errors());    
        } else {
            try {
                $input = $request->all();
                $input['password'] = bcrypt(trim($input['password']));
                $user = User::create($input);
                $user->assignRole('writer');
                $access_token = $user->createToken('Juicebox')->plainTextToken;
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'access_token' => $access_token
                ];
                $data_email = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];
                // send email here
                try {
                    dispatch(new SendMailJob($data_email));
                    return $this->responseOnSuccess(201,$data,"User Registered Successfully! Check email for welcome message!");
                } catch (\Exception $e){
                    return $this->responseOnError(404,"ERROR! ->".$e);
                }
            } catch (\Exception $e){
                return $this->responseOnError(404,"ERROR! ->".$e);
            }
        }
    }
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $access_token = $user->createToken('Juicebox')->plainTextToken;
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $access_token
            ];
            return $this->responseOnSuccess(201,$data);
        } 
        else{ 
            return $this->responseOnError(401,"Unauthorized!");
        } 
    }
    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return $this->responseOnSuccess(201,"Logged out successfully!");
        } catch (\Exception $e){
            return $this->responseOnError(401,"Error Logging Out! ->".$e);
        }
    }
    //
}
