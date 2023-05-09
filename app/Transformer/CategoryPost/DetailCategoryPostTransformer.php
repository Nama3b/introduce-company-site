<?php

namespace App\Transformer\CategoryPost;

use App\Models\CategoryPost;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;

class DetailCategoryPostTransformer extends TransformerAbstract
{

    /**
     * @param CategoryPost $post
     * @return array
     */
    #[ArrayShape([])] public function transform(CategoryPost $post): array
    {
        return [
            'id' => $post->id,
            'type' => $post->type,
            'position' => $post->position,
            'status' => $post->status
        ];
    }
}
