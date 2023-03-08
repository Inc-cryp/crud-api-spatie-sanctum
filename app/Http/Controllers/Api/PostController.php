<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Transformers\PostTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;


class PostController extends Controller
{
   
    public function Posts()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        $fractal = new Manager();
        $resource = new Collection($posts, new PostTransformer());
        return response()->json($fractal->createData($resource)->toArray());
    }

    
    public function OnlyPosts()
    {
        $user = auth()->user();
        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $fractal = new Manager();
        $resource = new Collection($posts, new PostTransformer());
        return response()->json($fractal->createData($resource)->toArray());
    }

    public function DetailPost($id)
    {
        $post = Post::findOrFail($id);
        return response()->json((new PostTransformer())->transform($post));
    }

    public function create(CreatePostRequest $request)
    {
        $user = auth()->user();
        Post::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'body' => $request->body
        ]);
        return response()->json(['message' => 'sukses']);
    }

    public function update($id)
    {
        $user = auth()->user();
        $post = Post::find($id);
        $checkPost = $this->checkPost($user, $post);
        if ($checkPost !== true) {
            return $checkPost;
        }

        $post->update(request()->except('user_id'));
        return response()->json(['message' => 'sukses']);
    }

    
    public function delete($id)
    {
        $user = auth()->user();
        $post = Post::find($id);
        $checkPost = $this->checkPost($user, $post);
        if ($checkPost !== true) {
            return $checkPost;
        }

        $post->delete();
        return response()->json(['message' => 'sukses']);
    }

    function checkPost($user, $post)
    {
        if (is_null($post)) {
            return response()->json(['message' => 'Post tidak ada'], 400);
        }

        if ($user->role->name == 'Contributor') {
            if ($post->user_id !== $user->id) {
                return response()->json(['message' => 'You are not own the post'], 400);
            }
        }
        return true;
    }
}
