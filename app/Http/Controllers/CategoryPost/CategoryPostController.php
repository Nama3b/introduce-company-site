<?php

namespace App\Http\Controllers\CategoryPost;

use App\Components\CategoryPost\Editor;
use App\Components\Post\Creator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryPost\EditCategoryPostRequest;
use App\Http\Requests\CategoryPost\StoreCategoryPostRequest;
use App\Models\CategoryPost;
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

        $options = CategoryPost::TYPE;
        $data = CategoryPost::pluck('type', 'id');

        $config = [
            "placeholder" => "Select multiple options..",
            "allowClear" => true,
        ];

        return (new $instance)
            ->render('admin.pages.index', compact('filter', 'editor', 'modal_size', 'create', 'options', 'config', 'data'));
    }

    /**
     * @param StoreCategoryPostRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryPostRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($request) {
            $status = (new Creator($request))->create();

            return optional($status)->id ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param CategoryPost $post
     * @param EditCategoryPostRequest $request
     * @return JsonResponse
     */
    public function edit(CategoryPost $post, EditCategoryPostRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($post, $request) {
            $status = (new Editor($request))->edit($post);

            return $status ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param CategoryPost $post
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function delete(CategoryPost $post, Request $request): mixed
    {
        return $this->withComponentErrorHandling(function () use ($post, $request) {
            $status = $post->delete();

            return $status ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param CategoryPost $post
     * @return JsonResponse|mixed
     */
    public function detail(CategoryPost $post): mixed
    {
        return $this->withComponentErrorHandling(function () use ($post) {

            return fractal()
                ->item($post)
                ->transformWith(new DetailCategoryPostTransformer())
                ->respond();
        });
    }

}
