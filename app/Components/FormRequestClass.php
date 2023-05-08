<?php

namespace App\Components;

use App\Support\WithFilterSupport;
use App\Support\WithPaginationLimit;
use App\Support\WithRequestSupport;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class FormRequestClass
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
     * @param $categoryPost
     * @return array
     */
    public function buildCreateData(bool $edit = false, $categoryPost = null): array
    {
        return $edit ? $this->buildCategoryPostData($edit, $categoryPost) :
            array_merge($this->buildCategoryPostData($edit, $categoryPost), [
                'type' => $this->makeField($categoryPost, $edit, 'type')
            ]);
    }

    /**
     * @param bool $edit
     * @param $categoryPost
     * @return array
     */
    #[ArrayShape([])] private function buildCategoryPostData(bool $edit = false, $categoryPost = null): array
    {
        return [
            'type' => $this->makeField($categoryPost, $edit, 'type'),
            'position' => $this->makeField($categoryPost, $edit, 'position'),
            'status' => $this->makeField($categoryPost, $edit, 'status')
        ];
    }

    /**
     * @param $categoryPost
     * @param $edit
     * @param string $fil
     * @return mixed
     */
    private function makeField($categoryPost, $edit, string $fil = ''): mixed
    {
        return $this->existField($edit) ?
            $categoryPost->{$fil} :
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
