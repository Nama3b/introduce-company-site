<?php

namespace App\Transformers\User;

use App\Models\Role;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{

    /**
     * @param Role $role
     * @return array
     */
    public function transform(Role $role): array
    {
        return [
            'name' => $role->name,
            'code' => $role->code,
            'permission' => $this->singlePermission($role),
        ];
    }

    /**
     * @param $role
     * @return mixed
     */
    private function singlePermission($role)
    {
        return $role->permissions()->pluck('permission_id')->toArray();
    }
}
