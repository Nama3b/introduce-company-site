<?php

namespace App\Components\CategoryPost;

use App\Components\Common\CategoryPostCommonClass;
use App\Models\CategoryPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class Creator extends CategoryPostCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @return Model|CategoryPost
     */
    public function create(): Model|CategoryPost
    {
        return CategoryPost::create($this->buildCreateData());
    }
}
