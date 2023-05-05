<?php

namespace App\Support;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;


trait HandleComponentError
{
    use HandleJsonResponses;

    /**
     * @param Closure $callback
     * @return JsonResponse|mixed
     */
    protected function withComponentErrorHandling(Closure $callback): mixed
    {
        try {
            return $callback();
        } catch (Exception $e) {
            return $this->message($e->getMessage())->respondBadRequest();
        }
    }

    /**
     * @param Closure $callback
     * @return mixed|void
     */
    protected function withViewErrorHandling(Closure $callback)
    {
        try {
            return $callback();
        } catch (Exception) {
            return abort(404);
        }
    }
}
