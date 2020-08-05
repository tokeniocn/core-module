<?php

use App\Models\User;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\OperateLog;
use Modules\Core\Services\Frontend\UserService;
use Modules\Core\Services\OperateLogService;

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

if (! function_exists('operate_log')) {
    /**
     * @param string $categoryDotOperate
     * @param string $log
     * @param array $options
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    function operate_log($categoryDotOperate, $log, array $options = [])
    {
        [$category, $operate] = explode('.', $categoryDotOperate, 2);

        $createOptions = $options['createOptions'] ?? [];
        unset($options['createOptions']);

        return resolve(OperateLogService::class)
            ->create(array_merge([
                'user_id' => $options['user_id'] ?? with_user_id(Auth::id()),
                'scene' => OperateLog::SCENE_FRONTEND,
                'category' => $category,
                'operate' => $operate,
                'log' => $log,
                'data' => [],
                'context' => [],
            ], $options), $createOptions);
    }
}

if (! function_exists('admin_operate_log')) {
    /**
     * @param string $categoryDotOperate
     * @param string $log
     * @param array $options
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws \Modules\Core\Exceptions\ModelSaveException
     */
    function admin_operate_log($categoryDotOperate, $log, array $options = [])
    {
        return operate_log($categoryDotOperate, $log, array_merge([
            'user_id' => with_admin_user_id(Auth::id()),
            'scene' => OperateLog::SCENE_ADMIN
        ], $options));
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
