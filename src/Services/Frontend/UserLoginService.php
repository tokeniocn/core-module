<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Modules\Core\Events\Frontend\UserBeforeLogin;
use Modules\Core\Events\Frontend\UserLoggedIn;
use Modules\Core\Exceptions\Frontend\Auth\UserEmailVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserMobileVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyException;
use Modules\Core\Models\Frontend\UserVerify;

class UserLoginService
{

    /**
     * @param $string
     * @param $password
     * @param array $options
     *
     * @return User
     * @throws UserEmailVerifyException
     * @throws UserMobileVerifyException
     */
    public function loginByGuessString($string, $password, array $options = [])
    {
        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        /** @var User $user */
        [
            'isEmail' => $isEmail,
            'isMobile' => $isMobile,
            'user' => $user
        ] = $userService->getByGuessString($string);

        event(new UserBeforeLogin($user, User::LOGIN_TYPE_PASSWORD));

        $userService->checkPassword($user, $password);

        $isEmail && $user->isEmailVerified();
        $isMobile && $user->isMobileverified();

        event(new UserLoggedIn($user));

        return $user;
    }

    /**
     * @param $string
     * @param $password
     * @param array $options
     *
     * @return User
     */
    public function loginByMobile($mobile, $code, array $options = [])
    {
        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        /** @var User $user */
        [
            'isEmail' => $isEmail,
            'user' => $user
        ] = $userService->getByGuessString($mobile);

        /** @var UserService $userService */
        $userVerifyService = resolve(UserVerifyService::class);

        $userVerifyService->getByKeyToken($mobile, $code, UserVerify::TYPE_MOBILE_LOGIN, array_merge([
            'setExpired' => true
        ], $options['userVerifyOptions'] ?? []));

        event(new UserBeforeLogin($user, User::LOGIN_TYPE_MOBILE));

        $isEmail && $user->isEmailVerified();

        event(new UserLoggedIn($user));

        return $user;
    }
}
