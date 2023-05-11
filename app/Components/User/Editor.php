<?php

namespace App\Components\User;

use App\Components\Common\UserCommonClass;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class Editor extends UserCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->update($this->buildCreateData(true, $user));
    }

}
