<?php

use App\Models\AdminUser;
use App\Models\User;
use Modules\Core\Services\Frontend\UserService;
use Modules\Core\Services\Admin\AdminOperateLogService;
use Illuminate\Support\Facades\Auth;

if (! function_exists('with_admin_user')) {
    /**
     * @param $userIdOrUser
     *
     * @return mixed
     */
    function with_admin_user($userIdOrUser)
    {
        if ($userIdOrUser instanceof AdminUser) {
            return $userIdOrUser;
        }

        return AdminUser::first($userIdOrUser);
    }
}

if (! function_exists('with_admin_user_id')) {
    /**
     * @param $userIdOrUser
     *
     * @return int
     * @throws InvalidArgumentException
     */
    function with_admin_user_id($userIdOrUser)
    {
        if (is_numeric($userIdOrUser)) {
            return $userIdOrUser;
        } elseif ($userIdOrUser instanceof AdminUser) {
            return $userIdOrUser->id;
        }

        throw new InvalidArgumentException('The argument must be instance of AdminUser or admin user id.');
    }
}

if (! function_exists('with_user')) {
    /**
     * @param $userIdOrUser
     *
     * @return User
     */
    function with_user($userIdOrUser)
    {
        if ($userIdOrUser instanceof User) {
            return $userIdOrUser;
        }

        return resolve(UserService::class)->getById($userIdOrUser);
    }
}


if (! function_exists('with_user_id')) {
    /**
     * @param $userIdOrUser
     *
     * @return int
     * @throws InvalidArgumentException
     */
    function with_user_id($userIdOrUser)
    {
        if (is_numeric($userIdOrUser)) {
            return $userIdOrUser;
        } elseif ($userIdOrUser instanceof User) {
            return $userIdOrUser->id;
        }

        throw new InvalidArgumentException('The argument must be instance of User or user id.');
    }
}

if (! function_exists('store_config')) {
    /**
     * @param string $key
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    function store_config($key, $value, array $options = [])
    {
        return config()->store($key, $value, $options);
    }
}

if (! function_exists('admin_opearte_log')) {
    /**
     * @param string $key
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    function admin_opearte_log($category, $opearte, $log, array $options = [])
    {
        return resolve(AdminOperateLogService::class)
            ->create(array_merge([
                'user_id' => with_admin_user_id(Auth::id()),
                'category' => $category,
                'operate' => $opearte,
                'log' => $log,
                'data' => $options['data'] ?? [],
                'context' => $opearte['context'] ?? [],
            ]), $options['createOptions'] ?? []);
    }
}


