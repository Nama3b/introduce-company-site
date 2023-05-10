<?php

namespace App\Transformer\User;

use App\Models\User;
use JetBrains\PhpStorm\ArrayShape;
use League\Fractal\TransformerAbstract;
use Storage;

class DetailUserTransformer extends TransformerAbstract
{

    /**
     * @param User $user
     * @return array
     */
    #[ArrayShape([])] public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'full_name' => $user->full_name,
            'birthday' => $user->birthday,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'image' => Storage::url($user->image),
            'status' => $user->status,
        ];
    }

}
