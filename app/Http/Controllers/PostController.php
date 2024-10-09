<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Validator;
use Illuminate\Support\Facades\Auth;
class PostController extends Controller
{
    public function index()
    {
        try {
            $user = auth('sanctum')->user();
            $user_id = $user->id;
            $permissionCheck = Auth::user()->hasRole('admin');
            $posts = Post::when(request()->is_published, function ($users) {
                                $users = $users->where('is_published', request()->is_published);
                            })
                            ->when($permissionCheck, function($query) use($user_id){
                                $query->where('user_id',$user_id);
                            })
                            ->with('comments')->paginate(20);
            return $this->responseOnSuccess(200,$posts);
        } catch (\Exception $e){
            return $this->responseOnError(404,"Not Found!");
        }
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $user = auth('sanctum')->user();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
            'is_published' => 'required'
        ]);
        if($validator->fails()){
            return $this->responseOnError(403,$validator->errors());     
        } else {
            DB::beginTransaction();
            try {
                $post = Post::create([
                    'user_id' => $user->id,
                    'title' => trim($input['title']),
                    'content' => trim($input['content']),
                    'slug' => Str::slug(trim($input['title'])),
                    'is_published' => trim($input['is_published']),
                ]);
            }  catch (\Exception $e) {
                DB::rollback();
                return $this->responseOnError(400,$e);   
            }
            DB::commit();
            return $this->responseOnSuccess(201,$post);   
        }
    } 
    public function show($id)
    {
        try {
            $posts = Post::find($id);
            if (is_null($posts)){
                return $this->responseOnError(404,"Not Found!");
            } else {
                return $this->responseOnSuccess(200,$posts);
            }
        } catch (\Exception $e){
            return $this->responseOnError(404,"Not Found!");
        }
    }
    public function showBySlug($slug)
    {
        try {
            $posts = Post::where('slug',$slug)->first();
            if (is_null($posts)){
                return $this->responseOnError(404,"Not Found!");
            } else {
                return $this->responseOnSuccess(200,$posts);
            }
        } catch (\Exception $e){
            return $this->responseOnError(404,"Not Found!");
        }
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
            'is_published' => 'required'
        ]);
        if($validator->fails()){
            return $this->responseOnError(403,$validator->errors());     
        }
        DB::beginTransaction();
        try {
            $post = Post::find($id);
            if (is_null($post)){
                return $this->responseOnError(404,"Not Found!");
            } else {
                $post->title = trim($input['title']);
                $post->slug = Str::slug(trim($input['title']));
                $post->content = trim($input['content']);
                $post->is_published = trim($input['is_published']);
                $post->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseOnError(400,$e);   
        }
        DB::commit();
        return $this->responseOnSuccess(201,$post);
    }
    public function destroy($id)
    {
        if (!$id){
            return $this->responseOnError(404,"Data Not Found!");
        } else {
            DB::beginTransaction();
            try{
                $posts = Post::find($id);
                if (is_null($posts)){
                    return $this->responseOnError(404,"Not Found!");
                } else {
                    Post::destroy($id);
                }
            } catch (\Exception $e){
                DB::rollback();
                return $this->responseOnError(400,$e);
            }
            DB::commit();
            return $this->responseOnSuccess(201,"Post Deleted!");
        }
    }
}
