<?php


namespace Modules\Core\Exceptions\Frontend\Auth;

use Exception;

class UserVerifyNotFundException extends Exception
{
    /**
     * @param $id
     *
     * @return UserNotFoundException
     */
    public static function withId($id)
    {
        return new static('User verify id: ' . $id . ' not found.');
    }
}
