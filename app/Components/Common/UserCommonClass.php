<?php

namespace App\Components\Common;

use App\Support\WithFilterSupport;
use App\Support\WithPaginationLimit;
use App\Support\WithRequestSupport;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserCommonClass
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
    #[ArrayShape([])] public function buildCreateData(bool $edit = false, $post = null): array
    {
        return [
            'user_name' => $this->makeField($post, $edit, 'user_name'),
            'email' => $this->makeField($post, $edit, 'email'),
            'password' => $this->makeField($post, $edit, 'password'),
            'full_name' => $this->makeField($post, $edit, 'full_name'),
            'birthday' => $this->makeField($post, $edit, 'birthday'),
            'phone_number' => $this->makeField($post, $edit, 'phone_number'),
            'address' => $this->makeField($post, $edit, 'address'),
            'image' => $this->request->hasFile('image') ? $this->uploadImage() : $post->image,
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
     * @return bool|string
     */
    private function uploadImage(): bool|string
    {
        return $this->request->file('image')->store('image', ['disk' => 'public']);
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
