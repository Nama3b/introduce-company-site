<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse|Response|RedirectResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse|Response|RedirectResponse
    {
        $this->validateLogin($request);

        if(method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            return $this->sendFailedLoginResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $request->session()->put('auth.password_confirmed_at', time());

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    protected function loggedOut(Request $request): Redirector|Application|RedirectResponse
    {
        return redirect('/admin/login');
    }
}
