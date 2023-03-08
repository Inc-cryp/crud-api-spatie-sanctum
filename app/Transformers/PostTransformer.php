<?php

namespace App\Transformers;

use App\Models\Comment;
use App\Models\Post;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        $komments = Comment::where('post_id', $post->id)->get();
        $fractal = new Manager();
        $resource = new Collection($komments, new CommentTransformer());
        $data = $fractal->createData($resource)->toArray();
        return [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'created_by' => $post->user->name,
            'created_at' => $post->created_at->format('j F Y'),
        ];
    }
}
