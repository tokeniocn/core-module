<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Http\Response;
use Illuminate\Cache\RateLimiter;
use Illuminate\Validation\ValidationException;

trait HasThrottles
{
    /**
     * @return RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * @param $key
     * @param int $maxAttempts
     *
     * @return bool
     */
    protected function hasTooManyAttempts($key, $maxAttempts = 3)
    {
        return $this->limiter()->tooManyAttempts($key, $maxAttempts);
    }


    /**
     * @param $key
     * @param int $decaySeconds
     */
    protected function incrementAttempts($key, $decaySeconds = 600)
    {
        $this->limiter()->hit($key, $decaySeconds);
    }

    /**
     * @param $key
     */
    protected function clearLoginAttempts($key)
    {
        $this->limiter()->clear($key);
    }

    /**
     * @param $key
     * @param int $maxAttempts
     * @param int $decaySeconds
     */
    protected function checkKeyAttempts($key, $maxAttempts = 3, $decaySeconds = 600)
    {
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'email' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->incrementAttempts($key, $decaySeconds);
    }

}
