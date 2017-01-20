<?php


namespace app\modules\click2call\models;


class LoginRequest implements RequestInterface
{
    private $username;

    private $key;

    public function __construct($username, $key)
    {
        $this->username = $username;
        $this->key = $key;
    }

    /**
     * @return RequestParameter[]
     */
    public function requestParameters()
    {
        return [
            new RequestParameter('action', 'Login'),
            new RequestParameter('SECRET', $this->key),
            new RequestParameter('username', $this->username),
        ];
    }
}