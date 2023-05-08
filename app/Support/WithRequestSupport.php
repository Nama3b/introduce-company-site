<?php

namespace App\Support;


use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait WithRequestSupport
{

	/**
	 * @return bool
	 */
	protected function hasSpecialRole(): bool
	{
		return $this->checkHarCodeRole($this->loadRoleForUser());
	}

	/**
	 * @param $user
	 * @return bool
	 */
	protected function checkHarCodeRole($user): bool
	{
		return (bool)@$user->roles->where('code', Employee::HARD_CODE_ROLE)->count();
	}

	/**
	 * @return User|Authenticatable|null
	 */
	protected function loadRoleForUser(): User|Authenticatable|null
	{
		return Auth::user()->loadMissing(['roles' => function ($query) {
			$query->where(function ($sub) {
				$sub->whereNotNull('valid_to')
					->whereRaw('? between user_roles.valid_from and user_roles.valid_to',
						[now()->format('Y-m-d')]);
			})->orWhere(function ($sub) {
				$sub->whereNull('valid_to')
					->where('user_roles.valid_from', '<=', now()->format('Y-m-d'));
			});
		}]);
	}
}
