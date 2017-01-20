<?php


namespace app\modules\click2call\models;


class LogoffRequest implements RequestInterface
{
    public function requestParameters()
    {
        return [
            new RequestParameter('Action', 'Logoff'),
        ];
    }
}