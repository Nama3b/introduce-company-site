<?php

namespace App\Components\CategoryPost;

use App\Components\FormRequestClass;
use App\Models\CategoryPost;
use Illuminate\Foundation\Http\FormRequest;

class Editor Extends FormRequestClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @param CategoryPost $categoryPost
     * @return bool
     */
    public function edit(CategoryPost $categoryPost): bool
    {
        return $categoryPost->update($this->buildCreateData(true, $categoryPost));
    }

}
