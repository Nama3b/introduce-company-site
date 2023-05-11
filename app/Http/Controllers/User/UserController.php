<?php

namespace App\Http\Controllers\User;

use App\Components\User\Creator;
use App\Components\User\Editor;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Support\WithPaginationLimit;
use App\Transformer\User\DetailUserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use WithPaginationLimit;

    /**
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('user');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request): mixed
    {
        list($instance, $filter, $editor, $model_size, $create) = $this->buildInstance($request);

        $options = [
            "placeholder" => "Select multiple options..",
            "allowClear" => true
        ];

        return (new $instance)
            ->render('admin.user.index', compact('filter', 'editor', 'model_size', 'create', 'options'));
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse|mixed
     */
    public function store(StoreUserRequest $request): mixed
    {
        return $this->withComponentErrorHandling(function () use ($request) {
            $status = (new Creator($request))->create();

            return optional($status)->id ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param User $user
     * @param EditUserRequest $request
     * @return JsonResponse|mixed
     */
    public function edit(User $user, EditUserRequest $request): mixed
    {
        return $this->withComponentErrorHandling(function () use ($user, $request) {
            $status = (new Editor($request))->edit($user);

            return optional($status)->id ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param User $user
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function delete(User $user, Request $request): mixed
    {
        return $this->withComponentErrorHandling(function () use ($user, $request) {
            $status = $user->delete();

            return $status ?
                $this->respondOk() :
                $this->respondBadRequest();
        });
    }

    /**
     * @param User $user
     * @return JsonResponse|mixed
     */
    public function details(User $user): mixed
    {
        return $this->withComponentErrorHandling(function () use ($user) {

            return fractal()
                ->item($user)
                ->transformWith(new DetailUserTransformer())
                ->respond();
        });
    }
}
