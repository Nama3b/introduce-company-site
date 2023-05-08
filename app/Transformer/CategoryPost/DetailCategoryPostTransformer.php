<?php

namespace App\Transformer\CategoryPost;

use App\Models\CategoryPost;
use JetBrains\PhpStorm\ArrayShape;
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
}
