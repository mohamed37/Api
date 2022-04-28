<?php

namespace App\Http\Controllers\Api;


use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $posts = Post::get();
        //return response($posts, 200, ['success']);

        return $this->apiResonsed($posts,200,'success');

    }

    public function show($id)
    {
        $post = Post::find($id);

        if($post){
         return $this->apiResonsed(new PostResource($post),200,'success');
        }
        return $this->apiResonsed(null, 401, 'The Post Not Found');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'name' => 'required',
            'group' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->apiResonsed(null,401,$validator->errors());

        }

        $post = Post::create($request->all());

        if($post){
            return $this->apiResonsed(new PostResource($post), 201, 'The Post Is Not Save');
        }

        return $this->apiResonsed(null,401,'Thr Post Is Not Save');
    }

    public function update($id,Request $request)
    {
        $post = Post::find($id);

        if(!$post){
            return $this->apiResonsed(null, 404, 'The Post Is Not Found');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'name' => 'required',
            'group' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->apiResonsed(null, 401, $validator->errors());

        }

        

        $post->update($request->all());

        if($post){
            return $this->apiResonsed(new PostResource($post), 201, 'The Post Is Save');
        }

        return $this->apiResonsed(null,401,'Thr Post Not Update');
    }


    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return $this->apiResonsed(null , 404, 'The Post Is Not Found');
        }

        $post->delete($id);
        return $this->apiResonsed($post, 200, 'Deleted Successfully');
    }
}
