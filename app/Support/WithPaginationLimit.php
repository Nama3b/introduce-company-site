<?php

namespace App\Support;


use Illuminate\Config\Repository;

trait WithPaginationLimit
{
    /**
     * Get pagination limit from the request data or default to the default limit.
     *
     * @param   $request
     * @param string $key
     * @param integer|null $default
     * @return Repository|int|mixed
     */
    public function getPaginationLimit($request, string $key = 'limit', int $default = null): mixed
    {
        $default = $default ?? config('api.per_page', 10);

        $limit = ($request->filled($key) && is_numeric($request->get($key))) ? (int) $request->get($key) : $default;

        if ($limit > 50) $limit = 50;

        return $limit;
    }
}
