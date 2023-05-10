<?php

namespace App\Transformer\Post;

use App\Models\Post;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;
use Storage;

class ListPostTransformer extends TransformerAbstract
{
    #[ArrayShape([])] public function transform(Post $post): array
    {
        return [
            'id' => $post->id,
            'post_type' => $post->post_type,
            'title' => $post->title,
            'image' => Storage::url($post->image),
            'url' => $post->url,
            'status' => $post->status
        ];
    }
}
