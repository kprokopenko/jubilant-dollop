<?php


namespace app\modules\click2call\models;


use yii\helpers\ArrayHelper;

class Connection
{
    /**
     * @var AsteriskSocket
     */
    private $socket;

    private $actionId;

    private $actionIdRequestParameter;

    private function generateActionId()
    {
        $this->actionId = mt_rand(1000000000, 9999999999);
    }

    private function createActionIdRequestParameter()
    {
        $this->actionIdRequestParameter = new RequestParameter('actionId', $this->actionId);
    }

    public function __construct($ip, $port)
    {
        $this->socket = new AsteriskSocket($ip, $port);
        $this->generateActionId();
        $this->createActionIdRequestParameter();
    }

    public function sendRequest(RequestInterface $request)
    {
        $requestParameters = ArrayHelper::merge(
            $request->requestParameters(),
            $this->actionIdRequestParameter
        );

        $this->socket->sendRequestParameters($requestParameters);
    }

    public function getSocket()
    {
        return $this->socket;
    }
}