<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\PermissionRequest;
use App\Models\Permission;
use App\Support\HandleComponentError;
use App\Transformers\User\PermissionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use HandleComponentError;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request): mixed
    {

        list($instance, $filter, $editor, $create) = $this->buildInstance($request);

        return (new $instance)
            ->render('admin.pages.index', compact('filter', 'editor', 'create'));
    }


    /**
     * @param Permission $permission
     * @return JsonResponse
     */
    public function detail(Permission $permission): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($permission) {
            return fractal()
                ->item($permission)
                ->transformWith(new PermissionTransformer())
                ->respond();
        });
    }

    /**
     * @param PermissionRequest $request
     * @param Permission $permission
     * @return JsonResponse
     */
    public function edit(PermissionRequest $request, Permission $permission): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($request, $permission) {
            $permission->update($request->except('_token'));

            return $this->respondOk();
        });
    }

    /**
     * @param Permission $permission
     * @return JsonResponse
     */
    public function delete(Permission $permission): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($permission) {
            $permission->delete();
            return $this->respondOk();
        });
    }

    /**
     * @param PermissionRequest $request
     * @return JsonResponse
     */
    public function store(PermissionRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($request) {
            Permission::create($request->all());
            return $this->respondOk();
        });
    }

}
