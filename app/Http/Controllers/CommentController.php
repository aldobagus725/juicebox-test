<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $user = auth('sanctum')->user();
        $validator = Validator::make($input, [
            'comment' => 'required',
            'post_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->responseOnError(403,$validator->errors());     
        } else {
            DB::beginTransaction();
            try {
                $comment = Comment::create([
                    'user_id' => $user->id,
                    'post_id' => trim($input['post_id']),
                    'comment' => trim($input['comment'])
                ]);
            }  catch (\Exception $e) {
                DB::rollback();
                return $this->responseOnError(400,$e,"Error on database entry!");   
            }
            DB::commit();
            return $this->responseOnSuccess(201,$comment,"Comment successfully created!");   
        }
    } 

    public function show($id)
    {
        try {
            $comment = Comment::find($id);
            if (is_null($comment)){
                return $this->responseOnError(404,"Not Found!","Comment Not Found!");
            } else {
                return $this->responseOnSuccess(200,$comment,"Comment Found!");
            }
        } catch (\Exception $e){
            return $this->responseOnError(404,$e,"Not Found!");
        }
    }

    public function showCommentByPostId($id)
    {
        try {
            $comment = Comment::where('post_id',$id)->paginate();
            if (is_null($comment)){
                return $this->responseOnError(404,"Not Found!","Comment Not Found!");
            } else {
                return $this->responseOnSuccess(200,$comment,"Comment Found!");
            }
        } catch (\Exception $e){
            return $this->responseOnError(404,$e,"Not Found!");
        }
    }
}
