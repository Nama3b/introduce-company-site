<?php

namespace App\Http\Controllers;

use App\Models\CategoryPost;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Support\HandleComponentError;
use App\Support\HandleJsonResponses;
use App\Support\WithPaginationLimit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HandleJsonResponses, HandleComponentError, WithPaginationLimit;

    const INSTANCE_DATA_TABLE = [
        'user' => [
            'database' => 'App\DataTables\User\UserDataTable',
            'role_create' => User::CREATE,
        ],
        'permission' => [
            'database' => 'App\DataTables\User\PermissionDataTable',
            'role_create' => Permission::CREATE,
        ],
        'role' => [
            'database' => 'App\DataTables\User\RoleDataTable',
            'role_create' => Role::CREATE,
        ],
        'post' => [
            'database' => 'App\DataTables\PostDataTable',
            'role_create' => Post::CREATE,
        ],
        'category_post' => [
            'database' => 'App\DataTables\CategoryPostDataTable',
            'role_create' => CategoryPost::CREATE,
        ]
    ];

    /**
     * @param $request
     * @return array
     */
    protected function buildInstance($request): array
    {
        return [
            self::INSTANCE_DATA_TABLE [$request->route()->getName()]['datatable'],
            __('generate.' . $request->route()->getName() . '.filter'),
            __('generate.' . $request->route()->getName() . '.editor'),
            __('generate.' . $request->route()->getName() . '.modal_size'),
            Gate::allows(self::INSTANCE_DATA_TABLE [$request->route()->getName()]['role_create'])
        ];
    }

    /**
     * @param $data
     * @param string $message
     * @return array
     */
    public function success($data = null, string $message = ''): array
    {
        return (['status' => 'success', 'data' => $data, 'message' => $message]);
    }

    /**
     * @param string $message
     * @return array
     */
    public function error(string $message = ''): array
    {
        $message = empty($message) ? $message = __('string.error_response_default') : $message;
        return (['status' => 'error', 'message' => $message]);
    }

    /**
     * @return string[]
     */
    public function unauth(): array
    {
        return (['status' => 'unauth']);
    }
}
