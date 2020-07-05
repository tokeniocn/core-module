<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Exceptions\Frontend\Auth\UserNotFoundException;

class UserService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
    }

    /**
     * @var User
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        return $this->queryOne($where, array_merge([
            'exception' => function () {
                return new UserNotFoundException(trans('core::exception.用户数据未找到'));
            }
        ], $options));
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getById($id, array $options = [])
    {
        $key = 'user:' . $id;

        return Cache::tags([$key])
            ->rememberForever($key, function () use ($id, $options) {
                return $this->queryGetById($id, $options);
            });
    }

    /**
     * @param $string
     * @param array $options
     *
     * @return array
     */
    public function getByGuessString($string, array $options = [])
    {
        $where = [];
        $isEmail = $isMobile = false;

        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $isEmail = true;
            $where['email'] = $string;
        } elseif (preg_match('/^[1]([3-9])[0-9]{9}$/', $string)) {
            $isMobile = true;
            $where['mobile'] = $string;
        } else {
            $where['username'] = $string;
        }

        return [
            'isEmail' => $isEmail,
            'isMobile' => $isMobile,
            'user' => $this->one($where, $options),
        ];
    }


    /**
     * @param $user
     * @param $password
     * @param array $options
     *
     * @return bool
     */

    public function checkPassword($user, $password, array $options = [])
    {
        $user = with_user($user);

        if (!$user || !$user->checkPassword($password)) {

            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : ValidationException::withMessages([
                    trans('core::exception.密码验证失败')
                ]);
            }

            return false;
        }

        return true;
    }

    /**
     * @param $userId
     * @param $payPassword
     * @param array $options
     *
     * @return bool
     */
    public function checkPayPassword($user, $payPassword, array $options = [])
    {
        $user = with_user($user);

        if (!$user || !$user->checkPayPassword($payPassword)) {
            $exception = $options['exception'] ?? true;

            if ($exception) {
                throw is_callable($exception) ? $exception() : ValidationException::withMessages([
                    trans('core::exception.支付密码验证失败')
                ]);;
            }

            return false;
        }

        return true;
    }
}
