<?php

namespace App\Http\Controllers\CategoryPost;

use App\Components\CategoryPost\Editor;
use App\Components\Post\Creator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryPost\EditCategoryPostRequest;
use App\Http\Requests\CategoryPost\StoreCategoryPostRequest;
use App\Models\CategoryPost;
use App\Models\Post;
use App\Support\WithPaginationLimit;
use App\Transformer\CategoryPost\DetailCategoryPostTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryPostController extends Controller
{
    use WithPaginationLimit;

    public function index(): RedirectResponse
    {
        return redirect()->route('category_post');
    }

    public function list(Request $request)
    {
        list($instance, $filter, $editor, $modal_size, $create) = $this->buildInstance($request);

        $options = Post::POST_TYPE;
        $data = Post::pluck('id', 'post_type');

        $config = [
            "placeholder" => "Select multiple options..",
            "allowClear" => true,
        ];

        return (new $instance)
            ->render('admin.pages.index', compact('filter', 'editor', 'modal_size', 'create', 'options', 'config', 'data'));
    }

    public function store(StoreCategoryPostRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($request) {
            $status = (new Creator($request))->create();

            return optional($status)->id ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    public function edit(CategoryPost $categoryPost, EditCategoryPostRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($categoryPost, $request) {
            $status = (new Editor($request))->edit($categoryPost);

            return $status ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    public function delete(CategoryPost $categoryPost, Request $request)
    {
        return $this->withComponentErrorHandling(function () use ($categoryPost, $request) {
            $status = $categoryPost->delete();

            return $status ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    public function detail(CategoryPost $categoryPost)
    {
        return $this->withComponentErrorHandling(function () use ($categoryPost) {

            return fractal()
                ->item($categoryPost)
                ->transformWith(new DetailCategoryPostTransformer())
                ->respond();
        });
    }

}
