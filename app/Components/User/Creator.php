<?php

namespace App\Components\User;

use App\Components\Common\UserCommonClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class Creator extends UserCommonClass
{

    /**
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @return Model|User
     */
    public function create(): Model|User
    {
        return User::create($this->buildCreateData());
    }

}
