<?php

namespace App\Components\Post;

use App\Components\FormRequestClass;
use App\Models\CategoryPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class Creator extends FormRequestClass
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
