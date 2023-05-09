<?php

namespace App\Components\Post;

use App\Components\PostCommonClass;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class Editor extends PostCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function edit(Post $post): bool
    {
        return $post->update($this->buildCreateData(true, $post));
    }

}
