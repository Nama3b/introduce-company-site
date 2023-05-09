<?php

namespace App\Components;

use App\Support\WithFilterSupport;
use App\Support\WithPaginationLimit;
use App\Support\WithRequestSupport;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class PostCommonClass
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

//    /**
//     * @param bool $edit
//     * @param null $post
//     * @return array
//     */
//    public function buildCreateData(bool $edit = false, $post = null): array
//    {
//        return $edit ? $this->buildPostData($edit, $post) :
//            array_merge($this->buildPostData($edit, $post), [
//                'post_type' => $this->makeField($post, $edit, 'post_type')
//            ]);
//    }

    /**
     * @param bool $edit
     * @param $post
     * @return array
     */
    #[ArrayShape([])] public function buildCreateData(bool $edit = false, $post = null): array
    {
        return [
            'post_type' => $this->makeField($post, $edit, 'post_type'),
            'title' => $this->makeField($post, $edit, 'title'),
            'description' => $this->makeField($post, $edit, 'description'),
            'image' => $this->makeField($post, $edit, 'image'),
            'url' => $this->makeField($post, $edit, 'url'),
            'status' => $this->makeField($post, $edit, 'status'),
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
