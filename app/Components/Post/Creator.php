<?php

namespace App\Components\Post;

use App\Components\Common\PostCommonClass;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class Creator extends PostCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @return Model|Post
     */
    public function create(): Model|Post
    {
        return Post::create($this->buildCreateData());
    }
}
