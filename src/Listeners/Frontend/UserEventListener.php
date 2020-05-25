<?php

namespace Modules\Core\Listeners\Frontend;

use Cache;
use Carbon\Carbon;
use App\Models\User;
use Modules\Core\Models\Frontend\UserDataHistory;

/**
 * Class UserEventListener.
 */
class UserEventListener
{

    /**
     * Listen to the User created event.
     *
     * @param User $user
     */
    public function created(User $user): void
    {
        $this->logPasswordHistory($user);
        $this->logPayPasswordHistory($user);
    }

    /**
     * Listen to the User updating event.
     *
     * @param User $user
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('pay_password') && ! $user->isDirty('pay_password_updated_at')) {
            $user->pay_password_updated_at = Carbon::now();
        }
    }

    /**
     * Listen to the User updated event.
     *
     * @param User $user
     */
    public function updated(User $user): void
    {
        // 更新缓存
        Cache::tags('user:' . $user->id)->flush();

        if ($user->isDirty('password')) {
            $this->logPasswordHistory($user);
        }

        if ($user->isDirty('pay_password')) {
            $this->logPayPasswordHistory($user);
        }

        if ($user->isDirty('email') && $user->isEmailVerified(false)) {
            $this->logEmailHistory($user);
        }

        if ($user->isDirty('mobile') && $user->isMobileVerified(false)) {
            $this->logMobileHistory($user);
        }
    }

    /**
     * @param User $user
     */
    protected function logPasswordHistory(User $user): void
    {
        $user->passwordHistories()->create([
            'data' => $user->password,
            'type' => UserDataHistory::TYPE_PASSWORD,
        ]);
    }

    /**
     * @param User $user
     */
    protected function logPayPasswordHistory(User $user): void
    {
        if ( ! empty($user->pay_password)) {
            $user->payPasswordHistories()->create([
                'data' => $user->pay_password,
                'type' => UserDataHistory::TYPE_PAY_PASSWORD,
            ]);
        }
    }

    /**
     * @param User $user
     */
    protected function logEmailHistory(User $user): void
    {
        if ( ! empty($user->email)) {
            $user->emailHistories()->create([
                'data' => $user->email,
                'type' => UserDataHistory::TYPE_EMAIL,
            ]);
        }
    }

    /**
     * @param User $user
     */
    protected function logMobileHistory(User $user): void
    {
        if ( ! empty($user->mobile)) {
            $user->mobileHistories()->create([
                'data' => $user->mobile,
                'type' => UserDataHistory::TYPE_MOBILE,
            ]);
        }
    }
}
