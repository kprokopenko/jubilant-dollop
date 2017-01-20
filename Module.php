<?php


namespace app\modules\click2call;


use app\modules\click2call\models\CallRequest;
use app\modules\click2call\models\ConnectException;
use app\modules\click2call\models\Connection;
use app\modules\click2call\models\LoginRequest;
use app\modules\click2call\models\LogoffRequest;
use yii\base\ErrorException;

class Module extends \yii\base\Module
{
    public $ip;

    public $port;

    public $username;

    public $key;

    public $emulate = false;

    public $debugInfo = false;

    public function call($phoneNumber, $callerId)
    {
        try {
            $connection = new Connection($this->ip, $this->port);
        } catch (ErrorException $e) {
            throw new ConnectException('Не удалось установить соединение с Asterisk');
        }

        if ($this->emulate) {
            $connection->getSocket()->enableEmulateRequests();
        }

        if ($this->debugInfo){
            $connection->getSocket()->enablePrintDebugInfo();
        }

        $connection->sendRequest(new LoginRequest($this->username, $this->key));
        $connection->sendRequest(new CallRequest($phoneNumber, $callerId));
        $connection->sendRequest(new LogoffRequest());
    }
}