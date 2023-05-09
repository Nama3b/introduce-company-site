<?php

namespace App\Components\CategoryPost;

use App\Components\CategoryPostCommonClass;
use App\Models\CategoryPost;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

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

    /**
     * @param bool $edit
     * @param $post
     * @return array
     */
    public function buildCreateData(bool $edit = false, $post = null): array
    {
        return $edit ? $this->buildCategoryPostData($edit, $post) :
            array_merge($this->buildCategoryPostData($edit, $post), [
                'type' => $this->makeField($post, $edit, 'type')
            ]);
    }

    /**
     * @param bool $edit
     * @param $post
     * @return array
     */
    #[ArrayShape([])] private function buildCategoryPostData(bool $edit = false, $post = null): array
    {
        return [
            'type' => $this->makeField($post, $edit, 'type'),
            'position' => $this->makeField($post, $edit, 'position'),
            'status' => $this->makeField($post, $edit, 'status')
        ];
    }

    /**
     * @param $post
     * @param $edit
     * @param string $fil
     * @return mixed
     */
    private function makeField($post, $edit, string $fil = ''): mixed
    {
        return $this->existField($edit) ?
            $post->{$fil} :
            $this->request->input($fil);
    }

    /**
     * @param string $fil
     * @return bool
     */
    private function existField(string $fil = ''): bool
    {
        return $this->request->filled($fil);
    }

}
