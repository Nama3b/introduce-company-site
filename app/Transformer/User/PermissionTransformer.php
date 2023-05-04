<?php

namespace App\Transformers\User;

use App\Models\Permission;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{

    /**
     * @param Permission $user
     * @return array
     */
    public function transform(Permission $user): array
    {
        return [
            'name' => $user->name,
            'description' => $user->description,
        ];
    }
}
