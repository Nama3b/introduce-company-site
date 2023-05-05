<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Support\HandleComponentError;
use App\Transformers\User\RoleTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class RoleController extends Controller
{
    use HandleComponentError;

    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request): mixed
    {

        list($instance, $filter, $editor, $create) = $this->buildInstance($request);

        $options = $this->buildPermission();

        $config = [
            "placeholder" => "Select multiple options...",
            "allowClear" => true,
        ];

        $permission = $this->permission();

        return (new $instance)
            ->render('admin.pages.index', compact('filter', 'editor', 'options', 'config', 'create', 'permission'));
    }

    /**
     * @return Collection
     */
    private function buildPermission(): Collection
    {
        return Permission::pluck('id', 'name');
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function detail(Role $role): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($role) {
            return fractal()
                ->item($role)
                ->transformWith(new RoleTransformer())
                ->respond();
        });
    }

    /**
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($request) {
            $role = Role::create($request->only(['name', 'code']));
            $role->permissions()->sync($request->input('permission'));
            $this->flushCacheSync();
            return $this->respondOk();
        });
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function edit(RoleRequest $request, Role $role): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($role, $request) {
            $role->update($request->except('_token'));

            $role->permissions()->sync($request->input('permission'));
            $this->flushCacheSync();
            return $this->respondOk();
        });
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function delete(Role $role): JsonResponse
    {
        return $this->withComponentErrorHandling(function () use ($role) {
            $role->delete();
            return $this->respondOk();
        });
    }

    /**
     *
     */
    private function flushCacheSync()
    {
        (new PermissionRole)->flushCache();
    }

    /**
     * @return string[][]
     */
    #[ArrayShape([])] public function permission(): array
    {
        return [
            'Thông báo' => [
                1 => 'CREATE',
                2 => 'VIEW',
                3 => 'EDIT',
                4 => 'DELETE',
            ],
            'Cửa hàng' => [
                5 => 'CREATE',
                6 => 'VIEW',
                7 => 'EDIT',
                8 => 'DELETE',
            ],
            'Sản phẩm' => [
                9 => 'CREATE',
                10 => 'VIEW',
                11 => 'EDIT',
                12 => 'DELETE',
            ],
            'Người đung' => [
                13 => 'CREATE',
                14 => 'VIEW',
                15 => 'EDIT',
                16 => 'DELETE',
            ],
            'Tin tức' => [
                17 => 'CREATE',
                18 => 'VIEW',
                19 => 'EDIT',
                20 => 'DELETE',
            ],
            'Version App' => [
                21 => 'CREATE',
                22 => 'VIEW',
                23 => 'EDIT',
                24 => 'DELETE',
            ],
            'Quyền' => [
                25 => 'CREATE',
                26 => 'VIEW',
                27 => 'EDIT',
                28 => 'DELETE',
            ],
        ];
    }
}
