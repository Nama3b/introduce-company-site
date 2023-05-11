<?php

namespace App\Components\CategoryPost;

use App\Components\Common\CategoryPostCommonClass;
use App\Models\CategoryPost;
use Illuminate\Foundation\Http\FormRequest;

class Editor Extends CategoryPostCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @param CategoryPost $post
     * @return bool
     */
    public function edit(CategoryPost $post): bool
    {
        return $post->update($this->buildCreateData(true, $post));
    }

}
