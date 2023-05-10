<?php

namespace App\Transformer\CategoryPost;

use App\Models\CategoryPost;
use App\Transformer\Post\ListPostTransformer;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class DetailCategoryPostTransformer extends TransformerAbstract
{

    /**
     * @param CategoryPost $categoryPost
     * @return array
     */
    #[ArrayShape([])] public function transform(CategoryPost $categoryPost): array
    {
        return [
            'id' => $categoryPost->id,
            'type' => $categoryPost->type,
            'position' => $categoryPost->position,
            'status' => $categoryPost->status
        ];
    }

    /**
     * @param CategoryPost $categoryPost
     * @return Collection
     */
    public function includePostTransformer(CategoryPost $categoryPost): Collection
    {
        return $this->collection($categoryPost->posts, new ListPostTransformer());
    }
}
