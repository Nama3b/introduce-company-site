<?php

namespace App\Components\Common;

use App\Support\WithFilterSupport;
use App\Support\WithPaginationLimit;
use App\Support\WithRequestSupport;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CategoryPostCommonClass
{
    use WithPaginationLimit, WithFilterSupport, WithRequestSupport;

    /**
     * The creating target request instance.
     *
     */
    protected FormRequest $request;


    /**
     * Create new request instance.
     */
    public function __construct(FormRequest $request)
    {
        $this->request = $request;
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
